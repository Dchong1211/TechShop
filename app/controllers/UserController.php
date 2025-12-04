<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class CustomerController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
        requireAdmin(); // Admin mới được dùng các API này
    }

    /* ============================
       LẤY TẤT CẢ USERS (CUSTOMER)
    ============================ */
    public function list() {
        return [
            "success" => true,
            "data" => $this->model->getAllCustomers()
        ];
    }

    /* ============================
            TÌM KIẾM USER
    ============================ */
    public function search($query) {
        return [
            "success" => true,
            "data" => $this->model->search($query)
        ];
    }

    public function detail($id) {
        $user = $this->model->getById($id);

        return [
            "success" => $user ? true : false,
            "data" => $user,
            "message" => $user ? "" : "Không tìm thấy người dùng!"
        ];
}


    /* ============================
              THÊM USER
    ============================ */
    public function create() {
        CSRF::requireToken();

        $data = [
            'ho_ten'     => $_POST['ho_ten'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'mat_khau'   => password_hash($_POST['mat_khau'] ?? '', PASSWORD_BCRYPT),
            'vai_tro'    => $_POST['vai_tro'] ?? 'user',
            'trang_thai' => $_POST['trang_thai'] ?? 1,
            'avatar'     => $_POST['avatar'] ?? ''
        ];

        $id = $this->model->create($data);

        echo json_encode([
            "success" => $id ? true : false,
            "id" => $id
        ], JSON_UNESCAPED_UNICODE);
    }

    /* ============================
              CẬP NHẬT USER
    ============================ */
    public function update($id, $data) {
        // Hash password nếu có nhập
        if (!empty($data['mat_khau'])) {
            $data['mat_khau'] = password_hash($data['mat_khau'], PASSWORD_BCRYPT);
        } else {
            unset($data['mat_khau']); // Không overwrite nếu bỏ trống
        }

        $ok = $this->model->update($id, $data);

        return [
            "success" => $ok ? true : false,
            "message" => $ok ? "Cập nhật thành công" : "Cập nhật thất bại"
        ];
    }

    /* ============================
         UPDATE USER - ROUTER CALL
    ============================ */
    public function updateFromPost() {
        CSRF::requireToken();

        $id = $_POST['id'];
        $data = [
            'ho_ten'     => $_POST['ho_ten'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'mat_khau'   => $_POST['mat_khau'] ?? '',
            'vai_tro'    => $_POST['vai_tro'] ?? 'user',
            'trang_thai' => $_POST['trang_thai'] ?? 1
        ];

        echo json_encode(
            $this->update($id, $data),
            JSON_UNESCAPED_UNICODE
        );
    }

    /* ============================
        ĐỔI TRẠNG THÁI USER
    ============================ */
    public function changeStatus($id, $status) {
        $ok = $this->model->changeStatus($id, $status);

        return [
            "success" => $ok,
            "message" => $ok ? "Đã cập nhật trạng thái" : "Cập nhật thất bại"
        ];
    }
}
