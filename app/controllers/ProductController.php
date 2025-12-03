<?php

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../helpers/qr.php';

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

        if ($ok) {
            // Lấy ID sản phẩm vừa tạo
            $newId = $this->model->lastId();

            // Tạo URL QR
            $url = "http://localhost/TechShop/product/" . $newId;

            // Lưu QR vào public/qr/
            $savePath = __DIR__ . "/../../public/qr/product_$newId.png";

            QR::make($url, $savePath);

            return ["success" => true, "message" => "Thêm thành công"];
        }

        return ["success" => false, "message" => "Thêm thất bại"];
    }


    // Admin: Cập nhật
    public function update() {
        requireAdmin();
        CSRF::requireToken();

        $id = intval($_POST["id"]);

        $ok = $this->model->update(
            $id,
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

        if ($ok) {
            // QR URL
            $url = "http://localhost/TechShop/product/" . $id;
            $savePath = __DIR__ . "/../../public/qr/product_$id.png";

            QR::make($url, $savePath);

            return ["success" => true, "message" => "Cập nhật thành công"];
        }

        return ["success" => false, "message" => "Cập nhật thất bại"];
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
