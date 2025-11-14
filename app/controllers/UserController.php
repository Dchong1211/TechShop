<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/mailer.php';
require_once __DIR__ . '/../config/env.php';

class UserController {

    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new User($db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ===================== REGISTER =====================
    public function register($name, $email, $password) {
        $result = $this->userModel->register($name, $email, $password);

        if (!$result['success']) 
            return $result;

        // gửi OTP email
        $subject = "TechShop - Mã OTP";
        $body = "<h3>Mã OTP của bạn: <b>{$result['otp']}</b></h3>";
        Mailer::send($email, $subject, $body);

        return [
            'success' => true,
            'message' => 'OTP đã được gửi!',
            'email'   => $email
        ];
    }

    // ===================== VERIFY EMAIL =====================
    public function verifyEmail($email, $otp) {
        return $this->userModel->verifyEmail($email, $otp);
    }

    // ===================== LOGIN =====================
    public function login($email, $password) {
        $result = $this->userModel->login($email, $password);
        if (!$result['success']) return $result;

        $user = $result['user'];

        // set session
        $_SESSION['user'] = [
            'id'     => $user['id'],
            'name'   => $user['name'],
            'email'  => $user['email'],
            'role'   => $user['role'],
            'status' => $user['status']
        ];

        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user'    => $_SESSION['user']
        ];
    }

    // ===================== GET CURRENT USER =====================
    public function get_current_user() {
        if (!isset($_SESSION['user'])) {
            return [
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập!'
            ];
        }

        return [
            'success' => true,
            'user'    => $_SESSION['user']
        ];
    }

    // ===================== FORGOT PASSWORD =====================
    public function forgotPassword($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        $user = $this->userModel->getByEmail($email);
        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $this->userModel->saveResetToken($email, $token, $expires);

        $link = env('BASE_URL') . "/reset-password?token=" . urlencode($token);

        Mailer::send($email, "TechShop - Đặt lại mật khẩu", "
            <h3>Xin chào {$user['name']}</h3>
            <p>Nhấn để đặt lại mật khẩu:</p>
            <a href='{$link}'>{$link}</a>
        ");

        return ['success' => true, 'message' => 'Link reset mật khẩu đã được gửi!'];
    }

    // ===================== RESET PASSWORD =====================
    public function resetPassword($token, $newPassword, $confirmPassword) {

        if ($newPassword !== $confirmPassword)
            return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp!'];

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword))
            return ['success' => false, 'message' => 'Mật khẩu mới quá yếu!'];

        $user = $this->userModel->getByResetToken($token);
        if (!$user)
            return ['success' => false, 'message' => 'Token không hợp lệ hoặc đã hết hạn!'];

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($user['id'], $hashed);

        return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
    }

    // ===================== LOGOUT =====================
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
        return ['success' => true, 'message' => 'Đã đăng xuất!'];
    }

    // ===================== ADMIN =====================
    public function adminListUsers() {
        return ['success' => true, 'data' => $this->userModel->getAll()];
    }

    public function adminToggleStatus($id) {
        $ok = $this->userModel->toggleStatus($id);

        return [
            'success' => $ok,
            'message' => $ok ? 'Cập nhật thành công!' : 'Cập nhật thất bại!'
        ];
    }
}
?>
