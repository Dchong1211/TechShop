<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';


/* =====================AUTH VIEWS===================== */
$router->get("/login", function () {
    require_once __DIR__ . "/../../public/admin/login.php";
}, ["guest"]);

$router->get("/register", function () {
    require_once __DIR__ . "/../../public/admin/register.php";
}, ["guest"]);

$router->get("/forgot-password", function () {
    require_once __DIR__ . "/../../public/admin/forgot_password.php";
}, ["guest"]);


/* =====================AUTH API===================== */
$router->post("/register", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->register($_POST['name'], $_POST['email'], $_POST['password']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

$router->post("/verify-email", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->verifyEmail($_POST['email'], $_POST['otp']),
        JSON_UNESCAPED_UNICODE
    );
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
    echo json_encode(
        (new AuthController())->logout(),
        JSON_UNESCAPED_UNICODE
    );
}, ["login"]);

$router->get("/api/profile", [AuthController::class, "get_current_user"], ["login"]);


/* =====================RESET PASSWORD===================== */
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->forgotPasswordOTP($_POST['email']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->verifyResetOTP($_POST['email'], $_POST['otp']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);

$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    echo json_encode(
        (new AuthController())->resetPasswordByOTP(
            $_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']
        ),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);


/* =====================CART API===================== */
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


/* =====================PRODUCT PUBLIC API===================== */
$router->get("/api/products", function () {
    echo json_encode((new ProductController())->list(), JSON_UNESCAPED_UNICODE);
});

$router->get("/product/{id}", function($id) {
    $_GET['id'] = $id; // truyền id vào GET cho file cũ
    require_once __DIR__ . "/../../public/user/product_detail.php";
});


/* =====================PRODUCT ADMIN API (POST ONLY)===================== */
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



/* =====================ADMIN VIEW PAGES===================== */
$router->get("/admin/products/add", function () {
    require_once __DIR__ . "/../../public/admin/add_products.php";
}, ["admin"]);

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


$router->get("/admin/products/list", function () {
    requireAdmin();

    $page = $_GET["page"] ?? 1;
    $limit = $_GET["limit"] ?? 10;
    $search = $_GET["search"] ?? "";

    echo json_encode(
        (new ProductController())->adminList($page, $limit, $search),
        JSON_UNESCAPED_UNICODE
    );
}, ["admin"]);

$router->get("/product/{id}", function($id) {
    require_once __DIR__ . "/../../public/user/product_detail.php";
});

/* ===================== USER PAGES (FE) ===================== */

// Trang chủ (alias cho cả '' và '/')
$router->get("", function () {
    require_once __DIR__ . "/../../public/user/index.php";
});
$router->get("/", function () {
    require_once __DIR__ . "/../../public/user/index.php";
});

// Danh sách sản phẩm
$router->get("/products", function () {
    require_once __DIR__ . "/../../public/user/product.php";
});

// Chi tiết sản phẩm
$router->get("/products/{id}", function ($id) {
    $_GET['id'] = $id;
    require_once __DIR__ . "/../../public/user/product_detail.php";
});

// Giỏ hàng (xem)
$router->get("/cart", function () {
    require_once __DIR__ . "/../../public/user/cart.php";
}, ["login"]);

// Giỏ hàng (POST thêm vào giỏ)
$router->post("/cart", function () {
    require_once __DIR__ . "/../../public/user/cart.php";
}, ["login"]);

// Thanh toán
$router->get("/checkout", function () {
    require_once __DIR__ . "/../../public/user/checkout.php";
}, ["login"]);

// Đơn hàng
$router->get("/orders", function () {
    require_once __DIR__ . "/../../public/user/orders.php";
}, ["login"]);

$router->get("/orders/{id}", function ($id) {
    $_GET['id'] = $id;
    require_once __DIR__ . "/../../public/user/orders_detail.php";
}, ["login"]);

// Profile
$router->get("/profile", function () {
    require_once __DIR__ . "/../../public/user/profile.php";
}, ["login"]);

$router->get("/profile/edit", function () {
    require_once __DIR__ . "/../../public/user/edit_profile.php";
}, ["login"]);

$router->get("/profile/password", function () {
    require_once __DIR__ . "/../../public/user/change_password.php";
}, ["login"]);

