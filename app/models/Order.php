<?php
// models/Order.php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    private $table = 'don_hang';

    public function __construct() {
        // sử dụng biến $conn global từ database.php
        global $conn;
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table}
                (id_khach_hang, tong_tien, phuong_thuc_thanh_toan,
                 ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, trang_thai_don)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // id_khach_hang = i
        // tong_tien = d (double)
        $stmt->bind_param("idsssss",
            $data['id_khach_hang'],
            $data['tong_tien'],
            $data['phuong_thuc_thanh_toan'],
            $data['ten_nguoi_nhan'],
            $data['sdt_nguoi_nhan'],
            $data['dia_chi_giao_hang'],
            $data['trang_thai_don']
        );

        $res = $stmt->execute();
        return $res ? $this->conn->insert_id : false;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET trang_thai_don = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function search($q, $limit = 50, $offset = 0) {
        $like = "%{$q}%";
        $sql = "SELECT * FROM {$this->table}
                WHERE ten_nguoi_nhan LIKE ?
                OR sdt_nguoi_nhan LIKE ?
                OR dia_chi_giao_hang LIKE ?
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function all($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM {$this->table}
                ORDER BY ngay_dat_hang DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
