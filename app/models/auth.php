<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // ================= REGISTER =================
    public function register($name, $email, $password) {

        if (!$name || !$email || !$password)
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email sai định dạng!'];

        // 1. Kiểm tra email đã tồn tại chưa
        $check = $this->conn->prepare("
            SELECT id, email_verified 
            FROM nguoi_dung 
            WHERE email = ? 
            LIMIT 1
        ");
        $check->bind_param("s", $email);
        $check->execute();
        $user = $check->get_result()->fetch_assoc();

        // 1.1. Nếu user đã tồn tại & ĐÃ xác minh -> chặn
        if ($user && (int)$user['email_verified'] === 1) {
            return [
                'success' => false,
                'message' => 'Email đã tồn tại!'
            ];
        }

        // 1.2. Nếu user đã tồn tại nhưng CHƯA xác minh -> resend OTP
        if ($user && (int)$user['email_verified'] === 0) {
            $otp = random_int(100000, 999999);
            $expires = date("Y-m-d H:i:s", time() + 300); // 5 phút

            $upd = $this->conn->prepare("
                UPDATE nguoi_dung
                SET otp = ?, otp_expires_at = ?
                WHERE id = ?
            ");
            $upd->bind_param("ssi", $otp, $expires, $user['id']);
            $upd->execute();

            return [
                'success' => true,
                'message' => 'OtpResend',
                'otp'     => $otp,
                'email'   => $email,
                'resend'  => true,
            ];
        }

        // 2. Nếu email chưa tồn tại -> tạo user mới
        $hashed  = password_hash($password, PASSWORD_DEFAULT);
        $otp     = random_int(100000, 999999);
        $expires = date("Y-m-d H:i:s", time() + 300);

        $stmt = $this->conn->prepare("
            INSERT INTO nguoi_dung (
                ho_ten, email, mat_khau, otp, otp_expires_at, email_verified, trang_thai
            )
            VALUES (?, ?, ?, ?, ?, 0, 1)
        ");
        $stmt->bind_param("sssis", $name, $email, $hashed, $otp, $expires);
        $stmt->execute();

        return [
            'success' => true,
            'message' => 'OtpSent',
            'otp'     => $otp,
            'email'   => $email,
            'resend'  => false,
        ];
    }

    // ================= VERIFY EMAIL =================
    public function verifyEmail($email, $otp) {

        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        if ($user['email_verified'] == 1)
            return ['success' => false, 'message' => 'Email đã được xác minh!'];

        if ($user['otp'] != $otp)
            return ['success' => false, 'message' => 'Mã OTP không đúng!'];

        if (strtotime($user['otp_expires_at']) < time())
            return ['success' => false, 'message' => 'Mã OTP đã hết hạn!'];

        $update = $this->conn->prepare("
            UPDATE nguoi_dung
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

    // ================= LOGIN =================
    public function login($username, $password)
    {
        if (!$username || !$password)
            return ['success' => false, 'message' => 'Thiếu tài khoản hoặc mật khẩu!'];

        // Login bằng email hoặc họ tên
        $stmt = $this->conn->prepare("
            SELECT * FROM nguoi_dung 
            WHERE email=? OR ho_ten=?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user)
            return ['success' => false, 'message' => 'Tài khoản không tồn tại!'];

        if (!password_verify($password, $user['mat_khau']))
            return ['success' => false, 'message' => 'Mật khẩu sai!'];

        if ((int)$user['email_verified'] === 0)
            return ['success' => false, 'message' => 'Email chưa xác minh!'];

        if ((int)$user['trang_thai'] !== 1)
            return ['success' => false, 'message' => 'Tài khoản đã bị khóa!'];

        return ['success' => true, 'user' => $user];
    }


    // ================= GET USER =================
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getAll() {
        return $this->conn
            ->query("SELECT * FROM nguoi_dung ORDER BY id DESC")
            ->fetch_all(MYSQLI_ASSOC);
    }

    // ================= RESET TOKEN =================
    public function saveResetToken($email, $token, $expires) {
        $stmt = $this->conn->prepare("
            UPDATE nguoi_dung SET reset_token=?, reset_expires_at=? WHERE email=?
        ");
        $stmt->bind_param("sss", $token, $expires, $email);
        return $stmt->execute();
    }

    public function getByResetToken($token) {
        $now = date("Y-m-d H:i:s");
        $stmt = $this->conn->prepare("
            SELECT * FROM nguoi_dung WHERE reset_token=? AND reset_expires_at >= ? LIMIT 1
        ");
        $stmt->bind_param("ss", $token, $now);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // ================= UPDATE PASSWORD =================
    public function updatePassword($id, $hashedPassword) {
        $stmt = $this->conn->prepare("
            UPDATE nguoi_dung 
            SET mat_khau=?, 
                reset_token=NULL, 
                reset_expires_at=NULL,
                reset_otp=NULL,
                reset_otp_expires=NULL
            WHERE id=?
        ");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    // ================= RESET OTP =================
    public function saveResetOTP($email, $otp, $expires) {
        $stmt = $this->conn->prepare("
            UPDATE nguoi_dung 
            SET reset_otp=?, reset_otp_expires=?
            WHERE email=?
        ");
        $stmt->bind_param("sss", $otp, $expires, $email);
        return $stmt->execute();
    }

    public function checkResetOTP($email, $otp) {
        $stmt = $this->conn->prepare("
            SELECT id, reset_otp_expires 
            FROM nguoi_dung 
            WHERE email=? AND reset_otp=? LIMIT 1
        ");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        if (!$data) return false;
        if (strtotime($data['reset_otp_expires']) < time()) return false;

        return $data['id'];
    }

    // ================= ADMIN UPDATE =================
    public function updateUser($id, $name, $role, $status) {
        $stmt = $this->conn->prepare("
            UPDATE nguoi_dung SET ho_ten=?, vai_tro=?, trang_thai=? WHERE id=?
        ");
        $stmt->bind_param("ssii", $name, $role, $status, $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $stmt = $this->conn->prepare("
            UPDATE nguoi_dung 
            SET trang_thai = IF(trang_thai=1,0,1)
            WHERE id=?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
