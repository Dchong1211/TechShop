<?php
// wishlist_action.php
session_start();
include_once __DIR__ . "/../models/WishlistModel.php";
include_once __DIR__ . "/../models/NotificationModel.php";
include_once __DIR__ . "/../../config.php"; // chứa $conn

$wishlist = new WishlistModel($conn);
$noti = new NotificationModel($conn);

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? null;

// phải có user login
if (empty($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'error' => 'Bạn cần đăng nhập']);
    exit;
}
$userId = (int)$_SESSION['user_id'];

if ($action === 'add') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $detailKey = $_POST['detail_key'] ?? null;
    if ($productId <= 0) { echo json_encode(['ok'=>false,'error'=>'product_id']); exit; }
    $ok = $wishlist->add($userId, $productId, $detailKey);
    echo json_encode(['ok' => $ok]);
    exit;
}

if ($action === 'remove') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $ok = $wishlist->remove($userId, $productId);
    echo json_encode(['ok' => $ok]);
    exit;
}

if ($action === 'list') {
    $data = $wishlist->getByUser($userId);
    echo json_encode(['ok' => true, 'data' => $data]);
    exit;
}

// fallback
echo json_encode(['ok'=>false,'error'=>'invalid_action']);
