<?php
// controllers/OrderController.php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class OrderController {
    private $model;
    private string $action;

    public function __construct() {
        requireAdmin(); // Chỉ admin mới truy cập
        $this->model = new OrderModel();
        $this->action = $_GET['action'] ?? 'list';
    }

    public function handle() {
        switch ($this->action) {
            case 'create':         return $this->create();
            case 'update_status':  return $this->updateStatus();
            case 'delete':         return $this->delete();
            case 'view':           return $this->view();
            case 'search':         return $this->search();
            case 'list':
            default:               return $this->list();
        }
    }

    private function isAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    // ===================== CREATE =====================
    private function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        CSRF::requireToken();

        $data = [
            'id_khach_hang' => intval($_POST['id_khach_hang']),
            'tong_tien' => floatval($_POST['tong_tien']),
            'phuong_thuc_thanh_toan' => $_POST['phuong_thuc_thanh_toan'] ?? '',
            'ten_nguoi_nhan' => $_POST['ten_nguoi_nhan'] ?? '',
            'sdt_nguoi_nhan' => $_POST['sdt_nguoi_nhan'] ?? '',
            'dia_chi_giao_hang' => $_POST['dia_chi_giao_hang'] ?? '',
            'trang_thai_don' => $_POST['trang_thai_don'] ?? 'cho_xac_nhan'
        ];

        $id = $this->model->create($data);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $id ? true : false, 'id' => $id]);
        } else {
            header("Location: /admin/orders.php?created=" . ($id ? 1 : 0) . "&id={$id}");
        }
        exit;
    }

    // ===================== UPDATE STATUS =====================
    private function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        CSRF::requireToken();

        $id = intval($_POST['id']);
        $status = $_POST['trang_thai_don'] ?? '';
        $ok = $this->model->updateStatus($id, $status);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $ok, 'id' => $id, 'status' => $status]);
        } else {
            header("Location: /admin/orders.php?updated=" . ($ok ? 1 : 0) . "&id={$id}");
        }
        exit;
    }

    // ===================== DELETE =====================
    private function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        CSRF::requireToken();

        $id = intval($_POST['id']);
        $ok = $this->model->delete($id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $ok, 'id' => $id]);
        } else {
            header("Location: /admin/orders.php?deleted=" . ($ok ? 1 : 0));
        }
        exit;
    }

    // ===================== VIEW =====================
    private function view() {
        $id = intval($_GET['id'] ?? 0);
        $order = $this->model->getById($id);

        header('Content-Type: application/json');
        echo json_encode($order);
        exit;
    }

    // ===================== SEARCH =====================
    private function search() {
        $q = $_GET['q'] ?? '';
        $res = $this->model->search($q);

        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }

    // ===================== LIST ALL =====================
    private function list() {
        $res = $this->model->all();

        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }
}

// ===================== RUN =====================
$controller = new OrderController();
$controller->handle();
