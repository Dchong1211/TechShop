<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../helpers/qr.php';
require_once __DIR__ . '/../helpers/pagination.php'; // CHỈNH LẠI TÊN FILE CHUẨN

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product();
    }

    // Lấy toàn bộ sản phẩm (client)
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

public function adminList($page = 1, $limit = 5, $search = "") {
    requireAdmin();

    $search = $this->model->conn->real_escape_string($search);

    $sql = "
        SELECT sp.*, dm.ten_dm AS category_name
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
        WHERE sp.ten_sp LIKE '%$search%'
        ORDER BY sp.id ASC
    ";

    $result = Pagination::query($this->model->conn, $sql, $page, $limit);

    return [
        "success" => true,
        "meta"    => $result["meta"],
        "data"    => $result["data"]
    ];
}


    // Admin: Tạo sản phẩm
        public function create() {
            requireAdmin();
            CSRF::requireToken();

            // URL ảnh đã upload từ imgbb (JS)
            $imgUrl = $_POST["hinh_anh"] ?? "";

            // Nếu không có URL => ảnh chưa upload
            if (empty($imgUrl)) {
                return [
                    "success" => false,
                    "message" => "Vui lòng chọn ảnh (upload imgbb bị lỗi)!"
                ];
            }

            $ok = $this->model->create(
                $_POST["id_dm"],
                $_POST["ten_sp"],
                floatval($_POST["gia"]),
                floatval($_POST["gia_khuyen_mai"] ?? 0),
                intval($_POST["so_luong_ton"] ?? 0),
                $imgUrl,
                $_POST["mo_ta_ngan"] ?? "",
                $_POST["chi_tiet"] ?? ""
            );

            if ($ok) {
                $newId = $this->model->lastId();

                // Tạo QR
                $url = "http://localhost/TechShop/product/" . $newId;
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
        $old = $this->model->getById($id);

        if (!$old) {
            return [
                "success" => false,
                "message" => "Sản phẩm không tồn tại!"
            ];
        }

        // Lấy URL ảnh từ hidden input
        // Nếu không có thì dùng ảnh cũ
        $imgUrl = $_POST["hinh_anh"] ?? $old["hinh_anh"];

        // Nếu vì lý do gì ảnh rỗng → fallback lại ảnh cũ
        if (empty($imgUrl)) {
            $imgUrl = $old["hinh_anh"];
        }

        $ok = $this->model->update(
            $id,
            $_POST["id_dm"],
            $_POST["ten_sp"],
            floatval($_POST["gia"]),
            floatval($_POST["gia_khuyen_mai"] ?? 0),
            intval($_POST["so_luong_ton"] ?? 0),
            $imgUrl, // Ảnh mới hoặc ảnh cũ
            $_POST["mo_ta_ngan"] ?? "",
            $_POST["chi_tiet"] ?? "",
            intval($_POST["trang_thai"])
        );

        if ($ok) {
            // Tạo lại QR Code NẾU MUỐN
            $url = "http://localhost/TechShop/product/" . $id;
            $savePath = __DIR__ . "/../../public/qr/product_$id.png";
            QR::make($url, $savePath);

            return [
                "success" => true,
                "message" => "Cập nhật thành công"
            ];
        }

        return [
            "success" => false,
            "message" => "Cập nhật thất bại"
        ];
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
