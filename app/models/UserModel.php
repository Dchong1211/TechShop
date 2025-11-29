<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'nguoi_dung';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
            (ho_ten, email, mat_khau, dien_thoai, dia_chi, vai_tro, email_verified, trang_thai) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii",
            $data['ho_ten'],
            $data['email'],
            $data['mat_khau'],
            $data['dien_thoai'],
            $data['dia_chi'],
            $data['vai_tro'],
            $data['email_verified'],
            $data['trang_thai']
        );
        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET ho_ten=?, email=?, dien_thoai=?, dia_chi=?, vai_tro=?, trang_thai=? 
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiii",
            $data['ho_ten'],
            $data['email'],
            $data['dien_thoai'],
            $data['dia_chi'],
            $data['vai_tro'],
            $data['trang_thai'],
            $id
        );
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
                WHERE ho_ten LIKE ? OR email LIKE ? 
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function all($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
