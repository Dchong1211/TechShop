<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class CustomerController {
    private $model;

    public function __construct() {
        requireAdmin();
        $this->model = new UserModel();
    }

    public function list() {
        return [
            "success" => true,
            "data"    => $this->model->getAllCustomers()
        ];
    }

    public function search($query) {
        return [
            "success" => true,
            "data"    => $this->model->search($query)
        ];
    }

    public function detail($id) {
        $user = $this->model->getById($id);
        return [
            "success" => $user ? true : false,
            "data"    => $user,
            "message" => $user ? "" : "Không tìm thấy người dùng!"
        ];
    }

    public function create() {
        CSRF::requireToken();

        $data = [
            'ho_ten'       => $_POST['ho_ten'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'mat_khau'     => password_hash($_POST['mat_khau'] ?? '', PASSWORD_BCRYPT),
            'avatar'       => $_POST['avatar'] ?? '',
            'vai_tro'      => $_POST['vai_tro'] ?? 'khach',
            'trang_thai'   => (int)($_POST['trang_thai'] ?? 1),
            'email_verified' => 0
        ];

        $id = $this->model->create($data);

        echo json_encode([
            "success" => (bool)$id,
            "id"      => $id,
            "message" => $id ? "Tạo user thành công" : "Tạo user thất bại"
        ], JSON_UNESCAPED_UNICODE);
    }

    public function updateFromPost() {
        CSRF::requireToken();

        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'ho_ten'     => $_POST['ho_ten'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'vai_tro'    => $_POST['vai_tro'] ?? 'khach',
            'trang_thai' => (int)($_POST['trang_thai'] ?? 1),
            'avatar'     => $_POST['avatar'] ?? ''
        ];

        if (!empty($_POST['mat_khau'])) {
            $data['mat_khau'] = password_hash($_POST['mat_khau'], PASSWORD_BCRYPT);
        }

        echo json_encode(
            $this->update($id, $data),
            JSON_UNESCAPED_UNICODE
        );
    }

    public function update($id, $data) {
        $ok = $this->model->update($id, $data);

        return [
            "success" => $ok,
            "message" => $ok ? "Cập nhật thành công!" : "Cập nhật thất bại!"
        ];
    }

    public function changeStatus($id, $status) {
        $ok = $this->model->changeStatus($id, (int)$status);
        return [
            "success" => $ok,
            "message" => $ok ? "Đã đổi trạng thái!" : "Đổi trạng thái thất bại!"
        ];
    }

    public function delete($id) {
        $ok = $this->model->delete((int)$id);
        return [
            "success" => $ok,
            "message" => $ok ? "Xóa tài khoản thành công!" : "Xóa tài khoản thất bại!"
        ];
    }
}
