<?php

require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection(); // Kết nối DB
    }

    public function getAll() {
        $sql = "
            SELECT sp.*, dm.ten_dm AS category_name
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            ORDER BY sp.id DESC
        "; // Lấy tất cả sản phẩm
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "
            SELECT sp.*, dm.ten_dm AS category_name
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_dm = dm.id
            WHERE sp.id = ?
        "; // Lấy SP theo ID

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet) {
        $sql = "
            INSERT INTO san_pham (id_dm,ten_sp,gia,gia_khuyen_mai,so_luong_ton,hinh_anh,mo_ta_ngan,chi_tiet)
            VALUES (?,?,?,?,?,?,?,?)
        "; // Thêm SP

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isddiiss", 
            $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet
        );
        return $stmt->execute();
    }

    public function update($id, $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet, $trang_thai) {
        $sql = "
            UPDATE san_pham 
            SET id_dm=?, ten_sp=?, gia=?, gia_khuyen_mai=?, so_luong_ton=?,
                hinh_anh=?, mo_ta_ngan=?, chi_tiet=?, trang_thai=?
            WHERE id=?
        "; // Sửa SP

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isddiissii",
            $dm, $ten, $gia, $gia_km, $sl, $img, $mo_ta_ngan, $chi_tiet, $trang_thai, $id
        );
        return $stmt->execute();
    }

    //Xóa sản phẩm
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM san_pham WHERE id=?"); 
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
