<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();

// Nhận input từ JSON hoặc form POST
$input = json_decode(file_get_contents("php://input"), true);
if (!$input && $_POST) $input = $_POST;

$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đăng nhập']);
    exit;
}

// Gọi Controller xử lý login
$response = $controller->login($email, $password);

// Nếu đăng nhập thành công
if ($response['success']) {
    $role = $response['role'] ?? 'user';
    
    if ($role === 'admin') {
        $response['redirect'] = '/TechShop/public/admin/index.php';
    } else {
        $response['redirect'] = '/TechShop/public/user/index.php';
    }
}

echo json_encode($response);

// <?php
// echo password_hash("admin123", PASSWORD_DEFAULT);
// admin: 
// tk: admin@gmail.com
// mk: admin123
// ?>
