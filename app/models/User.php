<?php
//Dừng chương trình nếu lỗi nên sử dụng require_once thay vì include
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Đăng ký tài khoản
    public function register($name, $email, $password) {
        // Kiểm tra email đã tồn tại chưa
        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $check->close();
            return false;
        }
        $check->close();

        // Mã hóa mật khẩu
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng mới
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Đăng nhập
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
