<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/CartController.php';
// Nếu sau này có Order, Product… thì require thêm ở đây




/* ===========VIEW PAGES=========== */

$router->get("/login", [UserController::class, "loginPage"]);
$router->get("/register", [UserController::class, "registerPage"]);
$router->get("/verify-email", [UserController::class, "verifyEmailPage"]);



/* ===========AUTHENTICATION=========== */

// Đăng ký + gửi OTP
$router->post("/register", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->register($_POST['name'], $_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
});

// Xác minh OTP đăng ký
$router->post("/verify-email", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->verifyEmail($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
});

// Đăng nhập
$router->post("/login", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->login($_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
});

// Đăng xuất
$router->post("/logout", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->logout(), JSON_UNESCAPED_UNICODE);
});



/* ===========USER PROFILE=========== */

$router->get("/profile", function () {
    requireLogin();
    $c = new UserController();
    echo json_encode($c->get_current_user(), JSON_UNESCAPED_UNICODE);
});



/* ===========RESET PASSWORD=========== */

// Gửi OTP quên mật khẩu
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->forgotPasswordOTP($_POST['email']), JSON_UNESCAPED_UNICODE);
});

// Xác minh OTP quên mật khẩu
$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->verifyResetOTP($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
});

// Reset mật khẩu bằng OTP
$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->resetPasswordByOTP($_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']),
        JSON_UNESCAPED_UNICODE
    );
});


/* ===========CART=========== */

// Lấy giỏ hàng
$router->get("/api/cart", function () {
    requireLogin();
    $c = new CartController();
    echo json_encode($c->getCart(), JSON_UNESCAPED_UNICODE);
});

// Thêm vào giỏ
$router->post("/api/cart/add", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->add(), JSON_UNESCAPED_UNICODE);
});

// Update số lượng
$router->post("/api/cart/update", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->update(), JSON_UNESCAPED_UNICODE);
});

// Xóa 1 sản phẩm
$router->post("/api/cart/remove", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->remove(), JSON_UNESCAPED_UNICODE);
});

// Clear giỏ
$router->post("/api/cart/clear", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->clear(), JSON_UNESCAPED_UNICODE);
});



/* ===========ADMIN=========== */

// Trang dashboard admin
$router->get("/admin/dashboard", function () {
    requireAdmin();
    echo "Admin Dashboard";
});

// Lấy danh sách user
$router->get("/admin/users/json", function () {
    requireAdmin();
    $c = new UserController();
    echo json_encode($c->adminListUsers(), JSON_UNESCAPED_UNICODE);
});

// Toggle trạng thái user
$router->post("/admin/users/toggle-status", function () {
    requireAdmin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->adminToggleStatus($_POST['id']), JSON_UNESCAPED_UNICODE);
});
