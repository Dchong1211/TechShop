<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product(); // Khởi tạo model
    }

    public function list() {
        return ["success"=>true, "data"=>$this->model->getAll()]; // Lấy danh sách
    }

    public function detail($id) {
        $item = $this->model->getById($id); // Lấy 1 sản phẩm
        return $item ? ["success"=>true,"data"=>$item] : ["success"=>false,"message"=>"Không tìm thấy sản phẩm!"];
    }

    public function create() {
        requireAdmin(); // Chỉ admin thêm
        CSRF::requireToken(); // Check CSRF

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

        return $ok ? ["success"=>true,"message"=>"Thêm thành công"]
                   : ["success"=>false,"message"=>"Thêm thất bại"];
    }

    public function update() {
        requireAdmin(); // Chỉ admin cập nhật
        CSRF::requireToken(); // Check CSRF

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

        return $ok ? ["success"=>true,"message"=>"Cập nhật thành công"]
                   : ["success"=>false,"message"=>"Cập nhật thất bại"];
    }

    public function delete() {
        requireAdmin(); // Chỉ admin được xóa
        CSRF::requireToken(); // Check CSRF

        $ok = $this->model->delete(intval($_POST["id"]));
        return $ok ? ["success"=>true,"message"=>"Xóa thành công"]
                   : ["success"=>false,"message"=>"Xóa thất bại"];
    }
}
