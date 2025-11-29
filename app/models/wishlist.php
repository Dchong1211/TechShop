<?php

require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private mysqli $conn;
    private string $table = 'danh_sach_yeu_thich';

    public function __construct($connection = null) {
        // Lấy biến $conn từ database.php
        global $conn;
        $this->conn = $connection ?? $conn;
    }

    // Thêm vào wishlist
    public function add(int $userId, int $productId, ?string $maChiTiet = null): bool {
        $sql = "INSERT INTO {$this->table} (id_nguoi_dung, id_san_pham, ma_chi_tiet)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $userId, $productId, $maChiTiet);
        return $stmt->execute();
    }

    // Xóa khỏi wishlist
    public function remove(int $userId, int $productId, ?string $maChiTiet = null): bool {
        if ($maChiTiet === null) {
            $sql = "DELETE FROM {$this->table}
                    WHERE id_nguoi_dung = ? AND id_san_pham = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
        } else {
            $sql = "DELETE FROM {$this->table}
                    WHERE id_nguoi_dung = ? AND id_san_pham = ? AND ma_chi_tiet = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iis", $userId, $productId, $maChiTiet);
        }
        return $stmt->execute();
    }

    // Danh sách wishlist theo người dùng
    public function listByUser(int $userId): array {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_nguoi_dung = ?
                ORDER BY ngay_them DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Kiểm tra sản phẩm đã tồn tại trong wishlist
    public function exists(int $userId, int $productId, ?string $maChiTiet = null): bool {
        if ($maChiTiet === null) {
            $sql = "SELECT COUNT(*) as cnt
                    FROM {$this->table}
                    WHERE id_nguoi_dung = ? AND id_san_pham = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
        } else {
            $sql = "SELECT COUNT(*) as cnt
                    FROM {$this->table}
                    WHERE id_nguoi_dung = ? AND id_san_pham = ? AND ma_chi_tiet = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iis", $userId, $productId, $maChiTiet);
        }

        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return intval($row['cnt']) > 0;
    }
}
