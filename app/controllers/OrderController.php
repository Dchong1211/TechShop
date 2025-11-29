<?php
// controllers/OrderController.php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

checkAuth();
$model = new Order();
$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'id_khach_hang' => intval($_POST['id_khach_hang']),
        'tong_tien' => floatval($_POST['tong_tien']),
        'phuong_thuc_thanh_toan' => $_POST['phuong_thuc_thanh_toan'],
        'ten_nguoi_nhan' => $_POST['ten_nguoi_nhan'],
        'sdt_nguoi_nhan' => $_POST['sdt_nguoi_nhan'],
        'dia_chi_giao_hang' => $_POST['dia_chi_giao_hang'],
        'trang_thai_don' => $_POST['trang_thai_don'] ?? 'cho_xac_nhan'
    ];
    $id = $model->create($data);
    header("Location: /admin/orders.php?created=" . ($id?1:0) . "&id={$id}");
    exit;
}

if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $status = $_POST['trang_thai_don'];
    $ok = $model->updateStatus($id, $status);
    header("Location: /admin/orders.php?updated=" . ($ok?1:0)."&id={$id}");
    exit;
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $ok = $model->delete($id);
    header("Location: /admin/orders.php?deleted=" . ($ok?1:0));
    exit;
}

if ($action === 'view') {
    $id = intval($_GET['id']);
    $order = $model->getById($id);
    header('Content-Type: application/json');
    echo json_encode($order);
    exit;
}

if ($action === 'search') {
    $q = $_GET['q'] ?? '';
    $res = $model->search($q);
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}

$res = $model->all();
header('Content-Type: application/json');
echo json_encode($res);
exit;