<?php
// controllers/CategoryController.php
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

checkAuth();
$model = new Category();
$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $ten_dm = $_POST['ten_dm'] ?? '';
    $mo_ta = $_POST['mo_ta'] ?? null;
    $trang_thai = intval($_POST['trang_thai'] ?? 1);
    $id = $model->create($ten_dm, $mo_ta, $trang_thai);
    header("Location: /admin/categories.php?created=" . ($id?1:0));
    exit;
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $ok = $model->update($id, $_POST['ten_dm'], $_POST['mo_ta'], intval($_POST['trang_thai']));
    header("Location: /admin/categories.php?updated=" . ($ok?1:0));
    exit;
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $ok = $model->delete($id);
    header("Location: /admin/categories.php?deleted=" . ($ok?1:0));
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
