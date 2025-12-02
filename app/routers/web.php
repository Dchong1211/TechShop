<?php
/* ===================== CORE DEPENDENCIES ===================== */
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

/* ========== CONTROLLERS ========== */
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

/* ======================== AUTH VIEWS ========================= */
$router->get("/login", fn() =>
    require_once __DIR__ . "/../../public/admin/login.php"
, ["guest"]);

$router->get("/register", fn() =>
    require_once __DIR__ . "/../../public/admin/register.php"
, ["guest"]);

$router->get("/forgot-password", fn() =>
    require_once __DIR__ . "/../../public/admin/forgot_password.php"
, ["guest"]);

/* ========================== AUTH API ========================= */
// Đăng ký
$router->post("/register", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->register(
        $_POST['name'], $_POST['email'], $_POST['password']
    ), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// Xác minh email
$router->post("/verify-email", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyEmail(
        $_POST['email'], $_POST['otp']
    ), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// Đăng nhập
$router->post("/login", function () {
    CSRF::requireToken();
    $username = $_POST['email'] ?? $_POST['username'] ?? '';
    $password = $_POST['password'];
    echo json_encode((new AuthController())->login($username, $password), JSON_UNESCAPED_UNICODE);
});

// Đăng xuất
$router->post("/logout", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->logout(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// Lấy thông tin user hiện tại
$router->get("/profile", [AuthController::class, "get_current_user"], ["login"]);

/* ===================== RESET PASSWORD API ==================== */
// Gửi OTP
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->forgotPasswordOTP($_POST['email']),
        JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// Verify OTP
$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyResetOTP(
        $_POST['email'], $_POST['otp']
    ), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// Cập nhật mật khẩu qua OTP
$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->resetPasswordByOTP(
        $_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']
    ), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

/* ========================== CART API ========================= */
$router->get("/api/cart", fn() =>
    json_encode((new CartController())->getCart(), JSON_UNESCAPED_UNICODE)
, ["login"]);

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

/* ===================== PRODUCT PUBLIC API ==================== */
$router->get("/api/products", fn() =>
    json_encode((new ProductController())->list(), JSON_UNESCAPED_UNICODE)
);

$router->get("/api/products/{id}", function ($id) {
    echo json_encode((new ProductController())->detail($id), JSON_UNESCAPED_UNICODE);
});

/* ===================== PRODUCT ADMIN API ===================== */
$router->post("/admin/products/add", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->create(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

$router->post("/admin/products/update", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->update(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

$router->post("/admin/products/delete", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->delete(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

$router->get("/admin/products/add", fn() =>
    require_once __DIR__ . "/../../public/admin/add_products.php"
, ["admin"]);

/* ========================= DASHBOARD API ===================== */
$router->get("/admin/api/dashboard/summary", fn() =>
    json_encode((new DashboardController())->summary(), JSON_UNESCAPED_UNICODE)
, ["admin"]);

$router->get("/admin/api/dashboard/monthly", fn() =>
    json_encode((new DashboardController())->monthly(), JSON_UNESCAPED_UNICODE)
, ["admin"]);

/* ======================== ADMIN PAGES ======================== */
$router->get("/admin/dashboard", fn() =>
    require_once __DIR__ . "/../../public/admin/dashboard.php"
, ["admin"]);

$router->get("/admin/users", fn() =>
    require_once __DIR__ . "/../../public/admin/users.php"
, ["admin"]);

$router->get("/admin/orders", fn() =>
    require_once __DIR__ . "/../../public/admin/orders.php"
, ["admin"]);

$router->get("/admin/products", fn() =>
    require_once __DIR__ . "/../../public/admin/products.php"
, ["admin"]);
