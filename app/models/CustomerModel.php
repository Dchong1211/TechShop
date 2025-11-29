<?php

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'nguoi_dung';

    public function __construct($conn = null) {
        global $conn as $globalConn;
        $this->conn = $conn ?? $globalConn;
    }

    // Create
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (ho_ten, email, mat_khau, dien_thoai, dia_chi, vai_tro, email_verified, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
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
        $res = $stmt->execute();
        if ($res) return $this->conn->insert_id;
        return false;
    }

    // Read one by id
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET ho_ten=?, email=?, dien_thoai=?, dia_chi=?, vai_tro=?, trang_thai=? WHERE id=?";
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

    // Delete
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }

    // Search (by name or email)
    public function search($q, $limit = 50, $offset = 0) {
        $like = "%{$q}%";
        $sql = "SELECT * FROM {$this->table} WHERE ho_ten LIKE ? OR email LIKE ? LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // List (simple)
    public function all($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
