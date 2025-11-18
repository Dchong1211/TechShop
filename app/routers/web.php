<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';


/* ===================== AUTH VIEW ===================== */
// login / register page
$router->get("/login", [AuthController::class, "loginPage"], ["guest"]);
$router->get("/register", [AuthController::class, "registerPage"], ["guest"]);
$router->get("/verify-email", [AuthController::class, "verifyEmailPage"], ["guest"]);


/* ===================== AUTH API ===================== */
// đăng ký
$router->post("/register", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->register($_POST['name'], $_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// xác minh email OTP
$router->post("/verify-email", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyEmail($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// đăng nhập
$router->post("/login", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->login($_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// đăng xuất
$router->post("/logout", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->logout(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// lấy thông tin user
$router->get("/profile", [AuthController::class, "get_current_user"], ["login"]);


/* ===================== RESET PASSWORD ===================== */
// gửi OTP reset
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->forgotPasswordOTP($_POST['email']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// xác minh OTP reset
$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyResetOTP($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

// đổi mật khẩu bằng OTP
$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->resetPasswordByOTP($_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);


/* ===================== CART API ===================== */
// lấy giỏ hàng
$router->get("/api/cart", function () {
    echo json_encode((new CartController())->getCart(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// thêm vào giỏ
$router->post("/api/cart/add", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->add(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// cập nhật số lượng
$router->post("/api/cart/update", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->update(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// xóa 1 sản phẩm khỏi giỏ
$router->post("/api/cart/remove", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->remove(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

// clear giỏ
$router->post("/api/cart/clear", function () {
    CSRF::requireToken();
    echo json_encode((new CartController())->clear(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

/* ===================== PRODUCT PUBLIC ===================== */
// lấy tất cả sản phẩm
$router->get("/api/products", function () {
    echo json_encode((new ProductController())->list(), JSON_UNESCAPED_UNICODE);
});

// xem chi tiết sản phẩm
$router->get("/api/products/{id}", function ($id) {
    echo json_encode((new ProductController())->detail($id), JSON_UNESCAPED_UNICODE);
});

/* ===================== PRODUCT ADMIN ===================== */
// thêm sản phẩm
$router->post("/admin/products/add", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->create(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

// sửa sản phẩm
$router->post("/admin/products/update", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->update(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

// xóa sản phẩm
$router->post("/admin/products/delete", function () {
    CSRF::requireToken();
    echo json_encode((new ProductController())->delete(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);