<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product();
    }

    // Lấy danh sách sản phẩm
    public function list() {
        return [
            "success" => true,
            "data" => $this->model->getAll()
        ];
    }

    // Lấy chi tiết sản phẩm
    public function detail($id) {
        $item = $this->model->getById($id);

        if (!$item) {
            return ["success" => false, "message" => "Không tìm thấy sản phẩm!"];
        }

        return ["success" => true, "data" => $item];
    }

    // Admin: thêm sản phẩm
    public function create() {
        requireAdmin();

        $ok = $this->model->create(
            $_POST["name"],
            $_POST["description"],
            $_POST["price"],
            $_POST["image"],
            $_POST["category_id"]
        );

        return $ok
            ? ["success" => true, "message" => "Thêm thành công"]
            : ["success" => false, "message" => "Thêm thất bại"];
    }

    // Admin: cập nhật sản phẩm
    public function update() {
        requireAdmin();

        $ok = $this->model->update(
            $_POST["id"],
            $_POST["name"],
            $_POST["description"],
            $_POST["price"],
            $_POST["image"],
            $_POST["category_id"]
        );

        return $ok
            ? ["success" => true, "message" => "Cập nhật thành công"]
            : ["success" => false, "message" => "Cập nhật thất bại"];
    }

    // Admin: xóa sản phẩm
    public function delete() {
        requireAdmin();

        $ok = $this->model->delete($_POST["id"]);

        return $ok
            ? ["success" => true, "message" => "Xóa thành công"]
            : ["success" => false, "message" => "Xóa thất bại"];
    }
}
