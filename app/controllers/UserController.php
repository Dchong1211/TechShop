<?php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../config/database.php';

class UserController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new User($db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    //Đăng ký
    public function register($name, $email, $password) {
        // Gọi model xử lý → Controller chỉ trả lại kết quả
        return $this->userModel->register($name, $email, $password);
    }

    //Đăng nhập
    public function login($email, $password) {
        $result = $this->userModel->login($email, $password);

        // Nếu đăng nhập thất bại, model đã có message → trả nguyên
        if (!$result['success']) {
            return $result;
        }

        // Nếu đăng nhập thành công → tạo session
        $user = $result['user'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user'
        ];

        return [
            'success' => true,
            'message' => $result['message'],
            'user' => $_SESSION['user']
        ];
    }

    //Đăng xuất
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        return ['success' => true, 'message' => 'Đăng xuất thành công!'];
    }

    //Kiểm tra trạng thái đăng nhập
    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    //Lấy thông tin user hiện tại
    public function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
    // // Quên mật khẩu
    // public function forgotPassword($email) {
    //     return $this->userModel->resetPassword($email);
    // }

}
?>
