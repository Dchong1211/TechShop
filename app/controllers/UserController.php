<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

requireAdmin();

$model = new User();
$action = $_GET['action'] ?? 'list';

switch ($action) {

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();

            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'email' => $_POST['email'] ?? '',
                'mat_khau' => password_hash($_POST['mat_khau'] ?? '', PASSWORD_BCRYPT),
                'dien_thoai' => $_POST['dien_thoai'] ?? null,
                'dia_chi' => $_POST['dia_chi'] ?? null,
                'vai_tro' => $_POST['vai_tro'] ?? 'khach',
                'email_verified' => intval($_POST['email_verified'] ?? 0),
                'trang_thai' => intval($_POST['trang_thai'] ?? 1)
            ];

            $id = $model->create($data);
            header("Location: /admin/users.php?success=" . ($id ? '1' : '0') . "&id={$id}");
        }
        break;

    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            $id = intval($_POST['id']);

            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'email' => $_POST['email'] ?? '',
                'dien_thoai' => $_POST['dien_thoai'] ?? null,
                'dia_chi' => $_POST['dia_chi'] ?? null,
                'vai_tro' => $_POST['vai_tro'] ?? 'khach',
                'trang_thai' => intval($_POST['trang_thai'] ?? 1)
            ];

            $ok = $model->update($id, $data);

            header("Location: /admin/users.php?success=" . ($ok ? '1' : '0') . "&id={$id}");
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            $id = intval($_POST['id']);
            $ok = $model->delete($id);
            header("Location: /admin/users.php?deleted=" . ($ok ? '1' : '0'));
        }
        break;

    case 'view':
        $id = intval($_GET['id']);
        $user = $model->getById($id);
        header("Content-Type: application/json");
        echo json_encode($user);
        break;

    case 'search':
        $q = $_GET['q'] ?? '';
        $users = $model->search($q);
        header("Content-Type: application/json");
        echo json_encode($users);
        break;

    case 'list':
    default:
        $users = $model->all();
        header("Content-Type: application/json");
        echo json_encode($users);
        break;
}
