<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class CustomerController {

    private $model;

    public function __construct() {
        $this->model = new UserModel();
        requireAdmin();
    }

    public function list() {
        return [
            "success" => true,
            "data" => $this->model->getAllCustomers()
        ];
    }

    public function detail($id) {
        $user = $this->model->getById($id);

        return [
            "success" => $user ? true : false,
            "data" => $user,
            "message" => $user ? "" : "User không tồn tại!"
        ];
    }

    public function create() {
        CSRF::requireToken();

        $data = [
            "ho_ten" => $_POST["ho_ten"],
            "email" => $_POST["email"],
            "mat_khau" => password_hash($_POST["mat_khau"], PASSWORD_BCRYPT),
            "avatar" => $_POST["avatar"] ?? "",
            "vai_tro" => $_POST["vai_tro"] ?? "khach",
            "trang_thai" => $_POST["trang_thai"] ?? 1
        ];

        $id = $this->model->create($data);

        echo json_encode([
            "success" => $id ? true : false,
            "id" => $id
        ]);
    }

    public function updateFromPost() {
        CSRF::requireToken();

        $id = $_POST["id"];

        $data = [
            "ho_ten" => $_POST["ho_ten"],
            "email" => $_POST["email"],
            "avatar" => $_POST["avatar"],
            "vai_tro" => $_POST["vai_tro"],
            "trang_thai" => $_POST["trang_thai"]
        ];

        if (!empty($_POST["mat_khau"])) {
            $data["mat_khau"] = password_hash($_POST["mat_khau"], PASSWORD_BCRYPT);
        }

        $ok = $this->model->update($id, $data);

        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }

    public function delete($id) {
        CSRF::requireToken();

        $ok = $this->model->delete($id);

        return [
            "success" => $ok,
            "message" => $ok ? "Xóa thành công" : "Xóa thất bại"
        ];
    }
}
