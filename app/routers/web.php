<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CartController.php';


/* ===========================================
   AUTH PAGES (VIEW)
=========================================== */
$router->get("/login", [AuthController::class, "loginPage"], ["guest"]);
$router->get("/register", [AuthController::class, "registerPage"], ["guest"]);
$router->get("/verify-email", [AuthController::class, "verifyEmailPage"], ["guest"]);


/* ===========================================
   AUTH (API)
=========================================== */
$router->post("/register", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->register($_POST['name'], $_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/verify-email", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->verifyEmail($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/login", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->login($_POST['email'], $_POST['password']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/logout", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->logout(), JSON_UNESCAPED_UNICODE);
}, ["login"]);


/* PROFILE */
$router->get("/profile", [AuthController::class, "get_current_user"], ["login"]);


/* ===========================================
   RESET PASSWORD
=========================================== */
$router->post("/forgot-password", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->forgotPasswordOTP($_POST['email']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/verify-reset-otp", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->verifyResetOTP($_POST['email'], $_POST['otp']), JSON_UNESCAPED_UNICODE);
}, ["guest"]);

$router->post("/reset-password-otp", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode(
        $c->resetPasswordByOTP($_POST['user_id'], $_POST['new_password'], $_POST['confirm_password']),
        JSON_UNESCAPED_UNICODE
    );
}, ["guest"]);


/* ===========================================
   CART API (CHUẨN, KHÔNG TRÙNG)
=========================================== */

$router->get("/api/cart", function () {
    $c = new CartController();
    echo json_encode($c->getCart(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/add", function () {
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->add(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/update", function () {
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->update(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/remove", function () {
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->remove(), JSON_UNESCAPED_UNICODE);
}, ["login"]);

$router->post("/api/cart/clear", function () {
    CSRF::requireToken();
    $c = new CartController();
    echo json_encode($c->clear(), JSON_UNESCAPED_UNICODE);
}, ["login"]);


/* ===========================================
   ADMIN
=========================================== */
$router->get("/admin/dashboard", function () {
    echo "Admin Dashboard";
}, ["admin"]);

$router->get("/admin/users/json", function () {
    $c = new AuthController();
    echo json_encode($c->adminListUsers(), JSON_UNESCAPED_UNICODE);
}, ["admin"]);

$router->post("/admin/users/toggle-status", function () {
    CSRF::requireToken();
    $c = new AuthController();
    echo json_encode($c->adminToggleStatus($_POST['id']), JSON_UNESCAPED_UNICODE);
}, ["admin"]);
