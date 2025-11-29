<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

checkAuth();

$model = new User();

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data = [
        'ho_ten' => $_POST['ho_ten'] ?? '',
        'email' => $_POST['email'] ?? '',
        'mat_khau' => password_hash($_POST['mat_khau'] ?? '', PASSWORD_BCRYPT),
        'dien_thoai' => $_POST['dien_thoai'] ?? null,
        'dia_chi' => $_POST['dia_chi'] ?? null,
        'vai_tro' => $_POST['vai_tro'] ?? 'khach',
        'email_verified' => $_POST['email_verified'] ?? 0,
        'trang_thai' => $_POST['trang_thai'] ?? 1
    ];
    $id = $model->create($data);
    if ($id) {
        header("Location: /admin/users.php?success=created&id={$id}");
        exit;
    } else {
        echo "Tạo user lỗi";
    }
}
elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $data = [
        'ho_ten' => $_POST['ho_ten'] ?? '',
        'email' => $_POST['email'] ?? '',
        'dien_thoai' => $_POST['dien_thoai'] ?? null,
        'dia_chi' => $_POST['dia_chi'] ?? null,
        'vai_tro' => $_POST['vai_tro'] ?? 'khach',
        'trang_thai' => $_POST['trang_thai'] ?? 1
    ];
    $ok = $model->update($id, $data);
    if ($ok) header("Location: /admin/users.php?success=updated&id={$id}");
    else echo "Cập nhật lỗi";
}
elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = intval($_POST['id']);
    $ok = $model->delete($id);
    header("Location: /admin/users.php?deleted=" . ($ok ? '1' : '0'));
    exit;
}
elseif ($action === 'view') {
    $id = intval($_GET['id']);
    $user = $model->getById($id);
    header('Content-Type: application/json');
    echo json_encode($user);
    exit;
}
elseif ($action === 'search') {
    $q = $_GET['q'] ?? '';
    $res = $model->search($q);
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}
else {
    // list
    $users = $model->all(100, 0);
    // render or return json
    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}
