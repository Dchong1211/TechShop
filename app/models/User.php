<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    //Đăng ký tài khoản
    public function register($name, $email, $password) {
        //Kiểm tra dữ liệu đầu vào
        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!'];
        }

        //Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email không hợp lệ!'];
        }

        //Kiểm tra độ mạnh của mật khẩu
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường và số!'];
        }

        //Kiểm tra tên và email trùng
        $check = $this->conn->prepare("SELECT id, name, email FROM users WHERE email = ? OR name = ?");
        $check->bind_param("ss", $email, $name);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            $check->close();

            if ($existing['email'] === $email) {
                return ['success' => false, 'message' => 'Email đã được sử dụng!'];
            } elseif ($existing['name'] === $name) {
                return ['success' => false, 'message' => 'Tên người dùng đã tồn tại!'];
            }
        }
        $check->close();

        //Mã hóa mật khẩu
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        //Thêm người dùng mới
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $hashed);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            return ['success' => true, 'message' => 'Đăng ký thành công!'];
        }

        return ['success' => false, 'message' => 'Lỗi khi đăng ký tài khoản!'];
    }

    //Đăng nhập
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Vui lòng nhập email và mật khẩu!'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email không hợp lệ!'];
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Sai email hoặc mật khẩu!'];
        }

        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user' => $user
        ];
    }
    // //Quên mật khẩu - gửi mật khẩu mới random
    // public function resetPassword($email) {
    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         return ['success' => false, 'message' => 'Email không hợp lệ!'];
    //     }

    //     // Kiểm tra email tồn tại
    //     $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
    //     $stmt->bind_param("s", $email);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result->num_rows === 0) {
    //         $stmt->close();
    //         return ['success' => false, 'message' => 'Email không tồn tại!'];
    //     }
    //     $user = $result->fetch_assoc();
    //     $stmt->close();

    //     // Tạo mật khẩu ngẫu nhiên (8 ký tự)
    //     $newPass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    //     $hashed = password_hash($newPass, PASSWORD_DEFAULT);

    //     // Cập nhật mật khẩu mới
    //     $update = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    //     $update->bind_param("si", $hashed, $user['id']);
    //     $update->execute();
    //     $update->close();

    //     // Gửi email
    //     $subject = "TechShop - Mật khẩu mới của bạn";
    //     $message = "Xin chào!\n\nMật khẩu mới của bạn là: {$newPass}\nVui lòng đăng nhập và đổi lại mật khẩu ngay sau khi vào hệ thống.";
    //     $headers = "From: no-reply@techshop.com";

    //     //Gửi mail thật bằng hàm mail() (chỉ hoạt động nếu PHP có SMTP)
    //     @mail($email, $subject, $message, $headers);

    //     return ['success' => true, 'message' => 'Mật khẩu mới đã được gửi tới email của bạn!'];
    // }
}
?>
