<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn; // Lưu kết nối DB vào class
    }

    // ======================== REGISTER ========================
    public function register($name, $email, $password) {

        // Kiểm tra thiếu dữ liệu
        if (!$name || !$email || !$password)
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!'];

        // Kiểm tra email hợp lệ
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email sai định dạng!'];

        // Kiểm tra email đã tồn tại chưa
        $check = $this->conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();

        if ($exists)
            return ['success' => false, 'message' => 'Email đã tồn tại!'];

        // Hash mật khẩu
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Tạo OTP xác minh email 6 chữ số
        $otp = rand(100000, 999999);
        // Hạn OTP: 5 phút
        $expires = date("Y-m-d H:i:s", time() + 300);

        // Lưu user với trạng thái chưa verify
        $stmt = $this->conn->prepare("
            INSERT INTO users (name, email, password, otp, otp_expires_at, email_verified, status)
            VALUES (?, ?, ?, ?, ?, 0, 1)
        ");
        $stmt->bind_param("sssis", $name, $email, $hashed, $otp, $expires);
        $stmt->execute();

        return [
            'success' => true,
            'message' => 'OtpSent',
            'otp'     => $otp
        ];
    }

    // ======================== VERIFY EMAIL ========================
    public function verifyEmail($email, $otp) {

        // Lấy user theo email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // Không tồn tại
        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        // Đã verify rồi
        if ($user['email_verified'] == 1)
            return ['success' => false, 'message' => 'Email đã được xác minh!'];

        // OTP sai
        if ($user['otp'] != $otp)
            return ['success' => false, 'message' => 'Mã OTP không đúng!'];

        // OTP hết hạn
        if (strtotime($user['otp_expires_at']) < time())
            return ['success' => false, 'message' => 'Mã OTP đã hết hạn!'];

        // Xác minh thành công → update trạng thái
        $update = $this->conn->prepare("
            UPDATE users
            SET email_verified=1,
                email_verified_at=NOW(),
                otp=NULL,
                otp_expires_at=NULL
            WHERE id=?
        ");
        $update->bind_param("i", $user['id']);
        $update->execute();

        return ['success' => true, 'message' => 'Xác minh email thành công!'];
    }

    // ======================== LOGIN ========================
    public function login($username, $password) {

        if (!$username || !$password)
            return ['success' => false, 'message' => 'Thiếu tài khoản hoặc mật khẩu!'];

        // Tìm user theo email hoặc username
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE email=? OR name=? 
            LIMIT 1
        ");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user)
            return ['success' => false, 'message' => 'Tài khoản không tồn tại!'];

        if (!password_verify($password, $user['password']))
            return ['success' => false, 'message' => 'Mật khẩu sai!'];

        if ($user['email_verified'] == 0)
            return ['success' => false, 'message' => 'Email chưa xác minh!'];

        if ((int)$user['status'] !== 1)
            return ['success' => false, 'message' => 'Tài khoản đã bị khóa!'];

        return ['success' => true, 'message' => 'Đăng nhập thành công!', 'user' => $user];
    }


    // ======================== GET USER ========================
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM users ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    }

    // ======================== RESET PASSWORD - LINK ========================
    public function saveResetToken($email, $token, $expires) {
        $stmt = $this->conn->prepare("
            UPDATE users SET reset_token=?, reset_expires_at=? WHERE email=?
        ");
        $stmt->bind_param("sss", $token, $expires, $email);
        return $stmt->execute();
    }

    public function getByResetToken($token) {
        $now = date("Y-m-d H:i:s");
        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE reset_token=? AND reset_expires_at >= ? LIMIT 1
        ");
        $stmt->bind_param("ss", $token, $now);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // ======================== UPDATE PASSWORD ========================
    public function updatePassword($id, $hashedPassword) {
        // Cập nhật mật khẩu + xoá token reset và xoá OTP reset
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET password=?, 
                reset_token=NULL, 
                reset_expires_at=NULL,
                reset_otp=NULL,
                reset_otp_expires=NULL
            WHERE id=?
        ");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    // ======================== RESET PASSWORD - OTP ========================
    public function saveResetOTP($email, $otp, $expires) {
        // Lưu OTP + thời gian hết hạn vào DB
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET reset_otp=?, reset_otp_expires=?
            WHERE email=?
        ");
        $stmt->bind_param("sss", $otp, $expires, $email);
        return $stmt->execute();
    }

    public function checkResetOTP($email, $otp) {
        // Lấy user khớp OTP
        $stmt = $this->conn->prepare("
            SELECT id, reset_otp_expires 
            FROM users 
            WHERE email=? AND reset_otp=? LIMIT 1
        ");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        // Không có user khớp OTP
        if (!$data) return false;

        // OTP hết hạn
        if (strtotime($data['reset_otp_expires']) < time()) return false;

        // Trả về id user
        return $data['id'];
    }

    // ======================== ADMIN UPDATE ========================
    public function updateUser($id, $name, $role, $status) {
        $stmt = $this->conn->prepare("UPDATE users SET name=?, role=?, status=? WHERE id=?");
        $stmt->bind_param("ssii", $name, $role, $status, $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $stmt = $this->conn->prepare("
            UPDATE users SET status = IF(status=1,0,1) WHERE id=?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
