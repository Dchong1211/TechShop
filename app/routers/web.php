<?php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../controllers/UserController.php';

// ========= VIEW PAGES =========

// view login
$router->get("/login", [UserController::class, "loginPage"]);

// view register
$router->get("/register", [UserController::class, "registerPage"]);

// view nhập otp
$router->get("/verify-email", [UserController::class, "verifyEmailPage"]);


// ========= API AUTH =========

// api gửi otp
$router->post("/register", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->register($_POST['name'], $_POST['email'], $_POST['password']),
        JSON_UNESCAPED_UNICODE
    );
});

// api verify otp
$router->post("/verify-email", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->verifyEmail($_POST['email'], $_POST['otp']),
        JSON_UNESCAPED_UNICODE
    );
});

// api login
$router->post("/login", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->login($_POST['email'], $_POST['password']),
        JSON_UNESCAPED_UNICODE
    );
});

// api logout
$router->post("/logout", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->logout(),
        JSON_UNESCAPED_UNICODE
    );
});

// api profile
$router->get("/profile", function () {
    requireLogin();
    $c = new UserController();
    echo json_encode(
        $c->get_current_user(),
        JSON_UNESCAPED_UNICODE
    );
});


// ========= ADMIN =========

// admin dashboard (view)
$router->get("/admin/dashboard", function () {
    requireAdmin();
    echo "Admin Dashboard";
});

// admin list user
$router->get("/admin/users/json", function () {
    requireAdmin();
    $c = new UserController();
    echo json_encode(
        $c->adminListUsers(),
        JSON_UNESCAPED_UNICODE
    );
});

// admin toggle user
$router->post("/admin/users/toggle-status", function () {
    requireAdmin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->adminToggleStatus($_POST['id']),
        JSON_UNESCAPED_UNICODE
    );
});

