<?php
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/Router.php';

// Khởi tạo router
$router = new Router();

// Load routes
require_once __DIR__ . '/../app/routers/web.php';

// Chạy router
$router->run();
