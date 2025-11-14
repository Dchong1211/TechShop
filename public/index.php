<?php
session_start();

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/router.php';

$router = new Router();
require_once __DIR__ . '/../app/routers/web.php';


$router->run();
