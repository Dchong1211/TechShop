<?php

require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private $conn;
    private $table = 'danh_sach_yeu_thich';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function add($userId, $productId, $maChiTiet = null) {
        $sql = "INSERT INTO {$this->table} (id_nguoi_dung, id_san_pham, ma_chi_tiet) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $userId, $productId, $maChiTiet);
        return $stmt->execute();
    }

    public function remove($userId, $productId, $maChiTiet = null) {
        if ($maChiTiet === null) {
            $sql = "DELETE FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
        } else {
            $sql = "DELETE FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ? AND ma_chi_tiet = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iis", $userId, $productId, $maChiTiet);
        }
        return $stmt->execute();
    }

    public function listByUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE id_nguoi_dung = ? ORDER BY ngay_them DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function exists($userId, $productId, $maChiTiet = null) {
        if ($maChiTiet === null) {
            $sql = "SELECT COUNT(*) cnt FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii",$userId,$productId);
        } else {
            $sql = "SELECT COUNT(*) cnt FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ? AND ma_chi_tiet = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iis",$userId,$productId,$maChiTiet);
        }
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        return $r['cnt'] > 0;
    }
}
