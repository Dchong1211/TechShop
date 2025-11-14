<?php
// Nạp model User
require_once __DIR__ . '/../models/User.php';
// Nạp file kết nối database
require_once __DIR__ . '/../config/database.php';
// Nạp helper gửi mail
require_once __DIR__ . '/../helpers/mailer.php';
// Nạp hàm env() để lấy biến môi trường
require_once __DIR__ . '/../config/env.php';

// Khai báo class UserController
class UserController {

    // Thuộc tính lưu instance của model User
    private $userModel;

    // Hàm khởi tạo
    public function __construct() {
        // Tạo kết nối DB
        $db = (new Database())->getConnection();
        // Khởi tạo model User với kết nối DB
        $this->userModel = new User($db);

        // Nếu session chưa khởi tạo thì start
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ===================== REGISTER =====================
    // Hàm xử lý đăng ký tài khoản
    public function register($name, $email, $password) {
        // Gọi model để xử lý đăng ký
        $result = $this->userModel->register($name, $email, $password);

        // Nếu thất bại thì trả kết quả luôn
        if (!$result['success']) 
            return $result;

        // Nếu thành công -> gửi OTP email xác minh tài khoản
        $subject = "TechShop - Mã OTP"; // Tiêu đề email
        $body = "<h3>Mã OTP của bạn: <b>{$result['otp']}</b></h3>"; // Nội dung email
        Mailer::send($email, $subject, $body); // Gửi mail

        // Trả phản hồi cho FE
        return [
            'success' => true,
            'message' => 'OTP đã được gửi!',
            'email'   => $email
        ];
    }

    // ===================== VERIFY EMAIL =====================
    // Hàm xác minh email bằng OTP
    public function verifyEmail($email, $otp) {
        // Gọi model để verify
        return $this->userModel->verifyEmail($email, $otp);
    }

    // ===================== LOGIN =====================
    // Hàm đăng nhập
    public function login($email, $password) {
        // Gọi model để kiểm tra đăng nhập
        $result = $this->userModel->login($email, $password);
        // Nếu đăng nhập fail thì trả kết quả luôn
        if (!$result['success']) return $result;

        // Lấy thông tin user từ kết quả
        $user = $result['user'];

        // Lưu thông tin user vào session
        $_SESSION['user'] = [
            'id'     => $user['id'],
            'name'   => $user['name'],
            'email'  => $user['email'],
            'role'   => $user['role'],
            'status' => $user['status']
        ];

        // Trả kết quả thành công + info user
        return [
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'user'    => $_SESSION['user']
        ];
    }

    // ===================== GET CURRENT USER =====================
    // Lấy thông tin user đang đăng nhập
    public function get_current_user() {
        // Nếu chưa có user trong session
        if (!isset($_SESSION['user'])) {
            // Trả thông báo chưa đăng nhập
            return [
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập!'
            ];
        }

        // Nếu có thì trả info user
        return [
            'success' => true,
            'user'    => $_SESSION['user']
        ];
    }

    // ===================== FORGOT PASSWORD (LINK) =====================
    // Quên mật khẩu: gửi link reset qua email (flow cũ, vẫn giữ)
    public function forgotPassword($email) {
        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        // Tìm user theo email
        $user = $this->userModel->getByEmail($email);
        // Nếu không có user
        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        // Tạo token reset ngẫu nhiên
        $token = bin2hex(random_bytes(32));
        // Thời gian hết hạn: +1 giờ
        $expires = date("Y-m-d H:i:s", time() + 3600);

        // Lưu token + thời gian hết hạn vào DB
        $this->userModel->saveResetToken($email, $token, $expires);

        // Tạo link reset mật khẩu
        $link = env('BASE_URL') . "/reset-password?token=" . urlencode($token);

        // Gửi email chứa link reset
        Mailer::send(
            $email,
            "TechShop - Đặt lại mật khẩu",
            "
            <h3>Xin chào {$user['name']}</h3>
            <p>Nhấn để đặt lại mật khẩu:</p>
            <a href='{$link}'>{$link}</a>
            "
        );

        // Trả kết quả cho FE
        return ['success' => true, 'message' => 'Link reset mật khẩu đã được gửi!'];
    }

    // ===================== RESET PASSWORD (LINK) =====================
    // Đặt lại mật khẩu bằng token (flow cũ)
    public function resetPassword($token, $newPassword, $confirmPassword) {

        // Kiểm tra nhập lại mật khẩu có khớp không
        if ($newPassword !== $confirmPassword)
            return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp!'];

        // Kiểm tra độ mạnh mật khẩu bằng regex
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword))
            return ['success' => false, 'message' => 'Mật khẩu mới quá yếu!'];

        // Lấy user từ token reset
        $user = $this->userModel->getByResetToken($token);
        // Nếu không có user -> token sai hoặc hết hạn
        if (!$user)
            return ['success' => false, 'message' => 'Token không hợp lệ hoặc đã hết hạn!'];

        // Hash mật khẩu mới
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        // Cập nhật mật khẩu trong DB
        $this->userModel->updatePassword($user['id'], $hashed);

        // Trả kết quả OK
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
    }

    // ===================== FORGOT PASSWORD BY OTP =====================
    // Quên mật khẩu: gửi mã OTP (flow mới dùng mã xác minh)
    public function forgotPasswordOTP($email) {
        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return ['success' => false, 'message' => 'Email không hợp lệ!'];

        // Tìm user theo email
        $user = $this->userModel->getByEmail($email);
        // Nếu không có user
        if (!$user)
            return ['success' => false, 'message' => 'Email không tồn tại!'];

        // Tạo OTP 6 chữ số ngẫu nhiên
        $otp = random_int(100000, 999999);
        // Thời gian hết hạn OTP: 5 phút
        $expires = date("Y-m-d H:i:s", time() + 300);

        // Lưu OTP + thời gian hết hạn vào DB
        $this->userModel->saveResetOTP($email, $otp, $expires);

        // Tiêu đề mail OTP
        $subject = "TechShop - OTP Đặt lại mật khẩu";
        // Nội dung mail OTP
        $body = "
            <h3>Xin chào {$user['name']}</h3>
            <p>Mã OTP đặt lại mật khẩu của bạn là:</p>
            <h2><b>{$otp}</b></h2>
            <p>OTP có hiệu lực trong 5 phút.</p>
        ";

        // Gửi email OTP
        Mailer::send($email, $subject, $body);

        // Trả kết quả
        return ['success' => true, 'message' => 'OTP đã gửi qua email!'];
    }

    // ===================== VERIFY RESET OTP =====================
    // Xác minh OTP đặt lại mật khẩu
    public function verifyResetOTP($email, $otp) {
        // Gọi model để kiểm tra OTP
        $userId = $this->userModel->checkResetOTP($email, $otp);

        // Nếu không hợp lệ hoặc hết hạn
        if (!$userId) {
            return [
                'success' => false,
                'message' => 'OTP không đúng hoặc đã hết hạn!'
            ];
        }

        // Nếu OK -> trả về user_id để FE dùng cho bước đổi mật khẩu
        return [
            'success' => true,
            'message' => 'OTP hợp lệ!',
            'user_id' => $userId
        ];
    }

    // ===================== RESET PASSWORD BY OTP =====================
    // Đặt lại mật khẩu mới bằng OTP (dùng user_id đã verify)
    public function resetPasswordByOTP($userId, $newPassword, $confirmPassword) {

        // Kiểm tra nhập lại mật khẩu có khớp không
        if ($newPassword !== $confirmPassword)
            return ['success' => false, 'message' => 'Mật khẩu nhập lại không khớp!'];

        // Kiểm tra độ mạnh mật khẩu
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword))
            return ['success' => false, 'message' => 'Mật khẩu mới quá yếu!'];

        // Hash mật khẩu mới
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu trong DB (và có thể clear OTP trong model)
        $this->userModel->updatePassword($userId, $hashed);

        // Trả kết quả OK
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
    }

    // ===================== LOGOUT =====================
    // Hàm đăng xuất
    public function logout() {
        // Xóa toàn bộ dữ liệu trong session
        $_SESSION = [];
        // Nếu đang dùng cookie session
        if (ini_get("session.use_cookies")) {
            // Lấy thông tin cookie hiện tại
            $p = session_get_cookie_params();
            // Set cookie hết hạn
            setcookie(
                session_name(),    // tên session
                '',                // giá trị rỗng
                time() - 42000,    // thời gian hết hạn trong quá khứ
                $p["path"],        // path
                $p["domain"],      // domain
                $p["secure"],      // secure
                $p["httponly"]     // httponly
            );
        }
        // Hủy session
        session_destroy();
        // Trả kết quả OK
        return ['success' => true, 'message' => 'Đã đăng xuất!'];
    }

    // ===================== ADMIN =====================
    // Admin: lấy danh sách user
    public function adminListUsers() {
        // Gọi model lấy tất cả user
        return ['success' => true, 'data' => $this->userModel->getAll()];
    }

    // Admin: bật / tắt trạng thái user
    public function adminToggleStatus($id) {
        // Gọi model để đổi status
        $ok = $this->userModel->toggleStatus($id);

        // Trả kết quả theo trạng thái
        return [
            'success' => $ok,
            'message' => $ok ? 'Cập nhật thành công!' : 'Cập nhật thất bại!'
        ];
    }
}
// Kết thúc file UserController
?>
