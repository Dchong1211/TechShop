<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/UserController.php';

/* ===================== AUTH VIEWS ===================== */
$router->get("/login", function () {
    require_once __DIR__ . "/../../public/admin/login.php";
}, ["guest"]);

$router->get("/register", function () {
    require_once __DIR__ . "/../../public/admin/register.php";
}, ["guest"]);

$router->get("/forgot-password", function () {
    require_once __DIR__ . "/../../public/admin/forgot_password.php";
}, ["guest"]);

/* ===================== AUTH API ===================== */
$router->post("/register", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->register($_POST['name'], $_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/verify-email", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyEmail($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/login", function () {
    CSRF::requireToken();
    $auth = new AuthController();
    $username = $_POST['email'] ?? $_POST['username'] ?? '';
    $password = $_POST['password'];
    echo json_encode($auth->login($username, $password), JSON_UNESCAPED_UNICODE);
});

$router->post("/logout", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->logout(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

/* ===== API PROFILE (KHÔNG ĐỤNG TỚI /profile UI) ===== */
$router->get("/api/profile", [AuthController::class, "get_current_user"], ["login"]);

/* ===================== RESET PASSWORD ===================== */
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->forgotPasswordOTP($_POST['email']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->verifyResetOTP($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    echo json_encode((new AuthController())->resetPasswordByOTP(
        $_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']
    ), JSON_UNESCAPED_UNICODE);
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

/* ===================== PRODUCT PUBLIC API ===================== */
$router->get("/api/products", fn() => print json_encode((new ProductController())->list(), JSON_UNESCAPED_UNICODE));
$router->get("/api/products/{id}", fn($id) => print json_encode((new ProductController())->detail($id), JSON_UNESCAPED_UNICODE));

/* ===================== ADMIN VIEW ===================== */
$router->get("/admin/dashboard", fn() => require __DIR__ . "/../../public/admin/dashboard.php", ["admin"]);
$router->get("/admin/users", fn() => require __DIR__ . "/../../public/admin/users.php", ["admin"]);
$router->get("/admin/orders", fn() => require __DIR__ . "/../../public/admin/orders.php", ["admin"]);
$router->get("/admin/products", fn() => require __DIR__ . "/../../public/admin/products.php", ["admin"]);
$router->get("/admin/products/add", fn() => require __DIR__ . "/../../public/admin/add_products.php", ["admin"]);

/* ===================== USER PAGES ===================== */

// Home
$router->get("/", fn() => require __DIR__ . "/../../public/user/index.php");

// Product list
$router->get("/products", fn() => require __DIR__ . "/../../public/user/product.php");

// Product detail
$router->get("/products/{id}", function ($id) {
    $_GET['id'] = $id;
    require __DIR__ . "/../../public/user/product_detail.php";
});

// Cart
$router->get("/cart", fn() => require __DIR__ . "/../../public/user/cart.php", ["login"]);

// Checkout
$router->get("/checkout", fn() => require __DIR__ . "/../../public/user/checkout.php", ["login"]);

// Orders
$router->get("/orders", fn() => require __DIR__ . "/../../public/user/orders.php", ["login"]);
$router->get("/orders/{id}", function ($id) {
    $_GET['id'] = $id;
    require __DIR__ . "/../../public/user/orders_detail.php";
}, ["login"]);

// Profile
$router->get("/profile", fn() => require __DIR__ . "/../../public/user/profile.php", ["login"]);

// Edit profile
$router->get("/profile/edit", fn() => require __DIR__ . "/../../public/user/edit_profile.php", ["login"]);

// Change password
$router->get("/profile/password", fn() => require __DIR__ . "/../../public/user/change_password.php", ["login"]);
