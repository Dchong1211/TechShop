<?php
// controllers/WishlistController.php
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

checkAuth();
$model = new Wishlist();
$action = $_GET['action'] ?? 'list';

// expects authenticated user id from session via helper, here demo: $_SESSION['user_id']
session_start();
$currentUser = $_SESSION['user_id'] ?? null;

if (!$currentUser) {
    http_response_code(401); echo "Unauthorized"; exit;
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $product = intval($_POST['product_id']);
    $maChiTiet = $_POST['ma_chi_tiet'] ?? null;
    $ok = $model->add($currentUser, $product, $maChiTiet);
    header("Location: /product.php?id={$product}&wishlist=" . ($ok?1:0));
    exit;
}

if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $product = intval($_POST['product_id']);
    $maChiTiet = $_POST['ma_chi_tiet'] ?? null;
    $ok = $model->remove($currentUser, $product, $maChiTiet);
    header("Location: /wishlist.php?removed=" . ($ok?1:0));
    exit;
}

if ($action === 'list') {
    $items = $model->listByUser($currentUser);
    header('Content-Type: application/json');
    echo json_encode($items);
    exit;
}

if ($action === 'exists') {
    $product = intval($_GET['product_id'] ?? 0);
    $maChiTiet = $_GET['ma_chi_tiet'] ?? null;
    $exists = $model->exists($currentUser, $product, $maChiTiet);
    header('Content-Type: application/json');
    echo json_encode(['exists' => $exists]);
    exit;
}

http_response_code(400); echo "Bad request";
exit;
