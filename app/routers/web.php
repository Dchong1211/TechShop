<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';


/* ===================== AUTH VIEW ===================== */
// Trang login admin
$router->get("/login", function () {
    require_once __DIR__ . "/../../public/admin/login.php";
}, ["guest"]);

// Trang đăng ký
$router->get("/register", function () {
    require_once __DIR__ . "/../../public/admin/register.php";
}, ["guest"]);

// Trang verify email
$router->get("/forgot-password", function () {
    CSRF::token();
    require_once __DIR__ . "/../../public/admin/forgot_password.php";
}, ["guest"]);

/* ===================== AUTH API ===================== */
// Đăng ký
$router->post("/register", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->register($_POST['name'], $_POST['email'], $_POST['password']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

// Xác minh email OTP
$router->post("/verify-email", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->verifyEmail($_POST['email'], $_POST['otp']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

// Đăng nhập (AJAX)
$router->post("/login", function () {
    CSRF::requireToken();

    $auth = new AuthController();
    $username = $_POST['email'] ?? $_POST['username'] ?? '';
    $password = $_POST['password'];

    echo json_encode($auth->login($username, $password), JSON_UNESCAPED_UNICODE);
});


/* ===================== LOGOUT ===================== */
$router->post("/logout", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->logout(),
        JSON_UNESCAPED_UNICODE
    );
}, ["login"]);


/* ===================== LẤY THÔNG TIN USER ===================== */
$router->get("/profile", [AuthController::class, "get_current_user"], ["login"]);


/* ===================== RESET PASSWORD ===================== */
// Gửi OTP reset
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->forgotPasswordOTP($_POST['email']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

// Xác minh OTP reset
$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->verifyResetOTP($_POST['email'], $_POST['otp']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

// Đổi mật khẩu bằng OTP
$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->resetPasswordByOTP($_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);


/* ===================== CART API ===================== */
$router->get("/api/cart", function () {
    echo json_encode((new CartController())->getCart(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/add", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->add(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/update", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->update(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/remove", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->remove(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/clear", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->clear(), JSON_UNESCAPED_UNICODE);
}, ["login"]);


/* ===================== PRODUCT PUBLIC ===================== */
$router->get("/api/products", function () {
    echo json_encode((new ProductController())->list(), JSON_UNESCAPED_UNICODE);
});

$router->get("/api/products/{id}", function ($id) {
    echo json_encode((new ProductController())->detail($id), JSON_UNESCAPED_UNICODE);
});


/* ===================== PRODUCT ADMIN ===================== */
// Thêm sản phẩm
$router->post("/admin/products/add", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->create(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

// Sửa sản phẩm
$router->post("/admin/products/update", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->update(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

// Xóa sản phẩm
$router->post("/admin/products/delete", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->delete(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);


/* ===================== ADMIN DASHBOARD ===================== */
$router->get("/admin/dashboard", function() {
    require_once __DIR__ . "/../../public/admin/dashboard.php";
}, ["admin"]);

$router->get("/admin/users", function () {
    require_once __DIR__ . "/../../public/admin/users.php";
}, ["admin"]);

$router->get("/admin/orders", function () {
    require_once __DIR__ . "/../../public/admin/orders.php";
}, ["admin"]);

$router->get("/admin/products", function () {
    require_once __DIR__ . "/../../public/admin/products.php";
}, ["admin"]);
