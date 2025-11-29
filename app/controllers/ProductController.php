<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product();
    }

    // Lấy toàn bộ sản phẩm
    public function list() {
        return [
            "success" => true,
            "data" => $this->model->getAll()
        ];
    }

    // Lấy chi tiết sản phẩm
    public function detail($id) {
        $item = $this->model->getById($id);

        return $item
            ? ["success" => true, "data" => $item]
            : ["success" => false, "message" => "Không tìm thấy sản phẩm!"];
    }

    // Lấy theo danh mục
    public function category($id_dm) {
        return [
            "success" => true,
            "data" => $this->model->getByCategory($id_dm)
        ];
    }

    // Admin: Tạo sản phẩm
    public function create() {
        requireAdmin();
        CSRF::requireToken();

        $ok = $this->model->create(
            $_POST["id_dm"],
            $_POST["ten_sp"],
            floatval($_POST["gia"]),
            floatval($_POST["gia_khuyen_mai"] ?? 0),
            intval($_POST["so_luong_ton"]),
            $_POST["hinh_anh"] ?? "",
            $_POST["mo_ta_ngan"] ?? "",
            $_POST["chi_tiet"] ?? ""
        );

        return $ok
            ? ["success" => true, "message" => "Thêm thành công"]
            : ["success" => false, "message" => "Thêm thất bại"];
    }

    // Admin: Cập nhật
    public function update() {
        requireAdmin();
        CSRF::requireToken();

        $ok = $this->model->update(
            intval($_POST["id"]),
            $_POST["id_dm"],
            $_POST["ten_sp"],
            floatval($_POST["gia"]),
            floatval($_POST["gia_khuyen_mai"] ?? 0),
            intval($_POST["so_luong_ton"]),
            $_POST["hinh_anh"] ?? "",
            $_POST["mo_ta_ngan"] ?? "",
            $_POST["chi_tiet"] ?? "",
            intval($_POST["trang_thai"])
        );

        return $ok
            ? ["success" => true, "message" => "Cập nhật thành công"]
            : ["success" => false, "message" => "Cập nhật thất bại"];
    }

    // Admin: Xóa
    public function delete() {
        requireAdmin();
        CSRF::requireToken();

        $ok = $this->model->delete(intval($_POST["id"]));

        return $ok
            ? ["success" => true, "message" => "Xóa thành công"]
            : ["success" => false, "message" => "Xóa thất bại"];
    }
}
