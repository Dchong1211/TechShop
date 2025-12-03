<?php

require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getNextId() {
        $res = $this->conn->query("SELECT MAX(id) AS max_id FROM san_pham");
        $row = $res->fetch_assoc();
        return $row["max_id"] ? $row["max_id"] + 1 : 1;
    }


    // Lấy tất cả sản phẩm đang bán
    public function getAll() {
        $sql = "
            SELECT sp.*, dm.ten_dm AS category_name
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            WHERE sp.trang_thai = 1
            ORDER BY sp.id ASC
        ";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm theo danh mục
    public function getByCategory($id_dm) {
        $sql = "
            SELECT sp.*, dm.ten_dm AS category_name
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            WHERE sp.id_dm = ? AND sp.trang_thai = 1
            ORDER BY sp.ngay_nhap ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_dm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy chi tiết sản phẩm
    public function getById($id) {
        $sql = "
            SELECT sp.*, dm.ten_dm AS category_name
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            WHERE sp.id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tạo sản phẩm
    public function create($dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet) {

        $id = $this->getNextId();

        $sql = "
            INSERT INTO san_pham (id, id_dm, ten_sp, gia, gia_khuyen_mai, so_luong_ton, hinh_anh, mo_ta_ngan, chi_tiet)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisddisss", 
            $id, $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet
        );

        return $stmt->execute();
    }


    // Cập nhật sản phẩm
    public function update($id, $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet, $trang_thai) {
        $sql = "
            UPDATE san_pham 
            SET id_dm=?, ten_sp=?, gia=?, gia_khuyen_mai=?, so_luong_ton=?, 
                hinh_anh=?, mo_ta_ngan=?, chi_tiet=?, trang_thai=? 
            WHERE id=?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isddiissii",
            $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet, $trang_thai, $id
        );
        return $stmt->execute();
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM san_pham WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function lastId() {
        return $this->conn->insert_id;
    }
}
