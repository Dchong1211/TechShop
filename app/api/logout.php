<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();
$response = $controller->logout();
echo json_encode($response);
?>