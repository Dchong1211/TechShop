<?php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../controllers/UserController.php';

// view login
$router->get("/login", [UserController::class, "loginPage"]);

// view register
$router->get("/register", [UserController::class, "registerPage"]);

// view nhập otp
$router->get("/verify-email", [UserController::class, "verifyEmailPage"]);

// api gửi otp
$router->post("/register", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->register($_POST['name'], $_POST['email'], $_POST['password']));
});

// api verify otp
$router->post("/verify-email", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode(
        $c->createUserAfterVerify($_POST['email'], $_POST['otp'])
    );
});

// api login
$router->post("/login", function () {
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->login($_POST['email'], $_POST['password']));
});

// api logout
$router->post("/logout", function () {
    requireLogin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->logout());
});

// api profile
$router->get("/profile", function () {
    requireLogin();
    $c = new UserController();
    echo json_encode($c->getCurrentUser());
});

// admin dashboard
$router->get("/admin/dashboard", function () {
    requireAdmin();
    echo "Admin Dashboard";
});

// admin list user
$router->get("/admin/users/json", function () {
    requireAdmin();
    $c = new UserController();
    echo json_encode($c->adminListUsers());
});

// admin toggle user
$router->post("/admin/users/toggle-status", function () {
    requireAdmin();
    CSRF::requireToken();
    $c = new UserController();
    echo json_encode($c->adminToggleStatus($_POST['id']));
});
