<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../controllers/AuthController.php';

// Khởi tạo controller
$controller = new AuthController();

// Nhận dữ liệu từ JSON hoặc form
$input = json_decode(file_get_contents("php://input"), true);
if (!$input && $_POST) $input = $_POST;

// Lấy dữ liệu
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

// Kiểm tra hợp lệ
if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đăng ký']);
    exit;
}

// Gọi controller để xử lý
$response = $controller->register($name, $email, $password);

// Trả JSON về frontend
echo json_encode($response);
?>