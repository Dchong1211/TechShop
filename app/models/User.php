<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Register -> trả OTP cho FE, chưa lưu vào DB
    public function register($name, $email, $password) {

        if (!$name || !$email || !$password)
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email sai định dạng!'];

        // check email tồn tại
        $check = $this->conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();

        if ($exists)
            return ['success' => false, 'message' => 'Email đã tồn tại!'];

        // hash pass + tạo OTP
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", time() + 300);

        // INSERT user vào DB (email_verified=0)
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


    // Lưu tài khoản sau khi user nhập đúng OTP
    public function saveVerifiedUser($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO users (name, email, password, otp, otp_expires_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss",
            $data['name'],
            $data['email'],
            $data['password'],
            $data['otp'],
            $data['expires']
        );
        return $stmt->execute();
    }

    // Verify OTP để kích hoạt tài khoản
    public function verifyEmail($email, $otp) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
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

    // Login user 
    public function login($email, $password) {

        if (!$email || !$password)
            return ['success' => false, 'message' => 'Thiếu email hoặc mật khẩu!'];

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        if (!password_verify($password, $user['password']))
            return ['success' => false, 'message' => 'Mật khẩu sai!'];

        if ($user['email_verified'] == 0)
            return ['success' => false, 'message' => 'Email chưa xác minh!'];

        if ((int)$user['status'] !== 1)
            return ['success' => false, 'message' => 'Tài khoản đã bị khóa!'];

        return ['success' => true, 'message' => 'Đăng nhập thành công!', 'user' => $user];
    }

    // Get user by email
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // Get user by id
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // List all users
    public function getAll() {
        return $this->conn->query("SELECT * FROM users ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    }

    // Save reset token
    public function saveResetToken($email, $token, $expires) {
        $stmt = $this->conn->prepare("
            UPDATE users SET reset_token=?, reset_expires_at=? WHERE email=?
        ");
        $stmt->bind_param("sss", $token, $expires, $email);
        return $stmt->execute();
    }

    // Get by token
    public function getByResetToken($token) {
        $now = date("Y-m-d H:i:s");
        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE reset_token=? AND reset_expires_at >= ? LIMIT 1
        ");
        $stmt->bind_param("ss", $token, $now);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // Update password
    public function updatePassword($id, $hashedPassword) {
        $stmt = $this->conn->prepare("
            UPDATE users SET password=?, reset_token=NULL, reset_expires_at=NULL WHERE id=?
        ");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    // Update user (admin)
    public function updateUser($id, $name, $role, $status) {
        $stmt = $this->conn->prepare("UPDATE users SET name=?, role=?, status=? WHERE id=?");
        $stmt->bind_param("ssii", $name, $role, $status, $id);
        return $stmt->execute();
    }

    // Toggle status
    public function toggleStatus($id) {
        $stmt = $this->conn->prepare("
            UPDATE users SET status = IF(status=1,0,1) WHERE id=?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
