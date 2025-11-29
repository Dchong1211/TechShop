<?php
// controllers/NotifyController.php
require_once __DIR__ . '/../models/Notify.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

checkAuth();
$model = new Notify();
$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'id_nguoi_dung' => intval($_POST['id_nguoi_dung']),
        'loai_thong_bao' => $_POST['loai_thong_bao'] ?? '',
        'noi_dung' => $_POST['noi_dung'] ?? '',
        'da_doc' => intval($_POST['da_doc'] ?? 0)
    ];
    $id = $model->create($data);
    header("Location: /admin/notify.php?created=" . ($id ? 1 : 0));
    exit;
}

if ($action === 'mark_read' && isset($_POST['id'])) {
    verify_csrf();
    $ok = $model->markRead(intval($_POST['id']));
    header("Location: /admin/notify.php?marked=" . ($ok ? 1 : 0));
    exit;
}

if ($action === 'delete' && isset($_POST['id'])) {
    verify_csrf();
    $ok = $model->delete(intval($_POST['id']));
    header("Location: /admin/notify.php?deleted=" . ($ok ? 1 : 0));
    exit;
}

if ($action === 'search') {
    $userId = intval($_GET['user'] ?? 0);
    $q = $_GET['q'] ?? '';
    $res = $model->searchByUser($userId, $q);
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}

// default list
$res = $model->all();
header('Content-Type: application/json');
echo json_encode($res);
exit;
