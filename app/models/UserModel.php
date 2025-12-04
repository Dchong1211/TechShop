<?php

require_once __DIR__ . '/../config/database.php';

class UserModel {
    public $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    /* ============================================
        LẤY DANH SÁCH USER
    ============================================ */
    public function getAll() {
        $sql = "SELECT * FROM nguoi_dung ORDER BY id ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ============================================
        LẤY CHI TIẾT USER
    ============================================ */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* ============================================
        SEARCH USER
    ============================================ */
    public function search($keyword) {
        $like = "%" . $keyword . "%";

        $stmt = $this->conn->prepare("
            SELECT * FROM nguoi_dung
            WHERE ho_ten LIKE ? OR email LIKE ?
            ORDER BY id DESC
        ");

        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ============================================
        TẠO USER (Admin tạo trong Dashboard)
    ============================================ */
    public function create($ho_ten, $email, $mat_khau, $avatar, $vai_tro, $trang_thai) {
        $sql = "
            INSERT INTO nguoi_dung (ho_ten, email, mat_khau, avatar, vai_tro, trang_thai, ngay_tao)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssi",
            $ho_ten,
            $email,
            $mat_khau,
            $avatar,
            $vai_tro,
            $trang_thai
        );

        return $stmt->execute();
    }

    /* ============================================
        CẬP NHẬT USER
        Khớp hoàn toàn với controller mình đã viết:
        update($id, $ho_ten, $email, $avatar, $vai_tro, $trang_thai, $mat_khau = null)
    ============================================ */
    public function update($id, $ho_ten, $email, $avatar, $vai_tro, $trang_thai, $mat_khau = null) {

        if ($mat_khau === null || $mat_khau === "") {

            // Không đổi mật khẩu
            $sql = "
                UPDATE nguoi_dung
                SET ho_ten=?, email=?, avatar=?, vai_tro=?, trang_thai=?
                WHERE id=?
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssii",
                $ho_ten,
                $email,
                $avatar,
                $vai_tro,
                $trang_thai,
                $id
            );

        } else {

            // Có đổi mật khẩu
            $sql = "
                UPDATE nguoi_dung
                SET ho_ten=?, email=?, avatar=?, vai_tro=?, trang_thai=?, mat_khau=?
                WHERE id=?
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssisi",
                $ho_ten,
                $email,
                $avatar,
                $vai_tro,
                $trang_thai,
                $mat_khau,
                $id
            );
        }

        return $stmt->execute();
    }

    /* ============================================
        XOÁ USER
    ============================================ */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
