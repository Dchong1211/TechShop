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

    // Đăng ký + gửi OTP email
    public function register($name, $email, $password) {
    $result = $this->userModel->register($name, $email, $password);
    if (!$result['success']) return $result;

    // gửi mail OTP
    $subject = "TechShop - Mã OTP";
    $body = "<h3>Mã của bạn: {$result['otp']}</h3>";

    Mailer::send($email, $subject, $body);

    return [
        'success' => true,
        'message' => "OTP đã được gửi!",
        'email' => $email  // FE cần để verify
    ];
}




    // tạo user sau khi verify otp
    public function createUserAfterVerify($email, $otp) {

        if (!isset($_SESSION['register_data'])) {
            return ['success' => false, 'message' => 'Không có dữ liệu đăng ký!'];
        }

        $data = $_SESSION['register_data'];

        if ($data['email'] !== $email)
            return ['success' => false, 'message' => 'Email không trùng với đăng ký!'];

        if ($data['otp'] != $otp)
            return ['success' => false, 'message' => 'Mã OTP không đúng!'];

        if (strtotime($data['expires']) < time())
            return ['success' => false, 'message' => 'Mã OTP đã hết hạn!'];

        $stmt = $this->userModel->conn->prepare("
            INSERT INTO users (name, email, password, email_verified, email_verified_at, status)
            VALUES (?, ?, ?, 1, NOW(), 1)
        ");
        $stmt->bind_param("sss", $data['name'], $data['email'], $data['password']);
        $stmt->execute();

        unset($_SESSION['register_data']);

        return ['success' => true, 'message' => 'Tạo tài khoản thành công!'];
    }

    // đăng nhập
    public function login($email, $password) {
        $result = $this->userModel->login($email, $password);
        if (!$result['success']) return $result;

        $user = $result['user'];
        $_SESSION['user'] = [
            'id'     => $user['id'],
            'name'   => $user['name'],
            'email'  => $user['email'],
            'role'   => $user['role'],
            'status' => $user['status']
        ];

        return [
            'success' => true,
            'message' => $result['message'],
            'user'    => $_SESSION['user']
        ];
    }

    // gửi mail reset
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

    // reset mật khẩu
    public function resetPassword($token, $newPassword, $confirmPassword) {

        if ($newPassword !== $confirmPassword)
            return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp!'];

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword))
            return ['success' => false, 'message' => 'Mật khẩu mới quá yếu!'];

        $user = $this->userModel->getByResetToken($token);
        if (!$user)
            return ['success' => false, 'message' => 'Token không hợp lệ hoặc hết hạn!'];

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($user['id'], $hashed);

        return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
    }

    // logout
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
        return ['success' => true, 'message' => 'Đã đăng xuất!'];
    }

    // lấy user hiện tại
    public function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }

    // admin lấy list user
    public function adminListUsers() {
        return ['success' => true, 'data' => $this->userModel->getAll()];
    }

    // admin khóa/mở user
    public function adminToggleStatus($id) {
        $ok = $this->userModel->toggleStatus($id);
        return [
            'success' => $ok,
            'message' => $ok ? 'Cập nhật thành công!' : 'Cập nhật thất bại!'
        ];
    }
}
?>
