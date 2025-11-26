<?php
require_once __DIR__ . '/../models/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/mailer.php';
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../config/env.php';

class AuthController {

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

        // Validation
        if (!Validation::required($name))
            return ['success' => false, 'message' => 'Tên không được để trống!'];

        if (!Validation::email($email))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        if (!Validation::strongPassword($password))
            return ['success' => false, 'message' => 'Mật khẩu quá yếu!'];

        $result = $this->userModel->register($name, $email, $password);

        if (!$result['success']) return $result;

        $subject = "TechShop - Mã OTP";
        $body    = "<h3>Mã OTP của bạn: <b>{$result['otp']}</b></h3>";

        Mailer::send($result['email'], $subject, $body);

        return [
            'success' => true,
            'message' => $result['resend']
                ? 'Email đã tồn tại nhưng chưa kích hoạt. OTP mới đã được gửi!'
                : 'OTP đã được gửi! Vui lòng kiểm tra email.',
            'email'   => $result['email'],
        ];
    }

    // ===================== VERIFY EMAIL (OTP) =====================
    public function verifyEmail($email, $otp) {
        return $this->userModel->verifyEmail($email, $otp);
    }

    // ===================== LOGIN =====================
    public function login($usernameOrEmail, $password) {

        if (!Validation::required($usernameOrEmail))
            return ['success' => false, 'message' => 'Vui lòng nhập email hoặc username!'];

        if (!Validation::required($password))
            return ['success' => false, 'message' => 'Vui lòng nhập mật khẩu!'];

        $result = $this->userModel->login($usernameOrEmail, $password);
        if (!$result['success']) return $result;

        $user = $result['user'];

        $_SESSION['user'] = [
            'id'     => $user['id'],
            'name'   => $user['ho_ten'],
            'email'  => $user['email'],
            'role'   => $user['vai_tro'],
            'status' => $user['trang_thai']
        ];

        return [
            'success' => true,
            'message' => "Đăng nhập thành công!",
            'user'    => $_SESSION['user']
        ];
    }

    // ===================== GET CURRENT USER =====================
    public function get_current_user() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Người dùng chưa đăng nhập!'];
        }
        return ['success' => true, 'user' => $_SESSION['user']];
    }

    // ===================== FORGOT PASSWORD (OTP) =====================
    public function forgotPasswordOTP($email) {

        if (!Validation::email($email))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        $user = $this->userModel->getByEmail($email);
        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        $otp = random_int(100000, 999999);
        $expires = date("Y-m-d H:i:s", time() + 300);

        $this->userModel->saveResetOTP($email, $otp, $expires);

        $subject = "TechShop - OTP Đặt lại mật khẩu";
        $body = "
            <h3>Xin chào {$user['ho_ten']}</h3>
            <p>Mã OTP đặt lại mật khẩu của bạn là:</p>
            <h2><b>{$otp}</b></h2>
            <p>OTP có hiệu lực trong 5 phút.</p>
        ";

        Mailer::send($email, $subject, $body);

        return ['success' => true, 'message' => 'OTP đã được gửi qua email!'];
    }

    // ===================== VERIFY RESET OTP =====================
    public function verifyResetOTP($email, $otp) {

        if (!Validation::email($email))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        if (!Validation::required($otp))
            return ['success' => false, 'message' => 'OTP không được bỏ trống!'];

        $userId = $this->userModel->checkResetOTP($email, $otp);

        if (!$userId) {
            return ['success' => false, 'message' => 'OTP không đúng hoặc đã hết hạn!'];
        }

        return [
            'success' => true,
            'message' => 'OTP hợp lệ!',
            'user_id' => $userId
        ];
    }

    // ===================== RESET PASSWORD (OTP) =====================
    public function resetPasswordByOTP($userId, $newPassword, $confirmPassword) {

        if (!Validation::strongPassword($newPassword))
            return ['success' => false, 'message' => 'Mật khẩu mới quá yếu!'];

        if ($newPassword !== $confirmPassword)
            return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp!'];

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($userId, $hashed);

        return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
    }

    // ===================== LOGOUT =====================
    public function logout() {
        unset($_SESSION['user']);
        return ['success' => true, 'message' => 'Đã đăng xuất!'];
    }
}
?>