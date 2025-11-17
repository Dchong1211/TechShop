<?php
session_start();

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/router.php';

// Lấy URI từ .htaccess
$uri = $_GET['uri'] ?? '/';

// Khởi tạo router
$router = new Router($uri);

// Load routes
require_once __DIR__ . '/../app/routers/web.php';

// Chạy router
$router->run();
