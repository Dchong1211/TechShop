<?php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

// ==================== AUTH PAGES ====================

$router->get("/login", [UserController::class, "loginPage"]);
$router->post("/login", function () {
    CSRF::requireToken();                 //  Chống CSRF
    $c = new UserController();
    echo json_encode($c->login($_POST['email'], $_POST['password']));
});

$router->get("/register", [UserController::class, "registerPage"]);
$router->post("/register", function () {
    CSRF::requireToken();                 // Chống CSRF
    $c = new UserController();
    echo json_encode($c->register($_POST['name'], $_POST['email'], $_POST['password']));
});

$router->post("/logout", function () {
    requireLogin();                       // Chỉ user login mới được logout
    $c = new UserController();
    echo json_encode($c->logout());
});

// ==================== USER PROTECTED AREA ====================

$router->get('/profile', function () {
    requireLogin();                       // Chỉ user đăng nhập mới truy cập
    $c = new UserController();
    echo json_encode($c->getCurrentUser());
});

// Test route kiểm tra session
$router->get('/user-test', function () {
    $c = new UserController();
    echo "<pre>";
    print_r($c->getCurrentUser());
    echo "</pre>";
});


// ==================== ADMIN PROTECTED AREA ====================

$router->get('/admin/dashboard', function () {
    requireAdmin();                       // Chỉ admin mới vào được
    echo "Admin Dashboard";
});
