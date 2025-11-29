<?php

require_once __DIR__ . '/../config/database.php';

class Notify {
    private $conn;
    private $table = 'thong_bao';

    public function __construct($conn = null) {
        global $conn as $globalConn;
        $this->conn = $conn ?? $globalConn;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (id_nguoi_dung, loai_thong_bao, noi_dung, da_doc) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $data['id_nguoi_dung'], $data['loai_thong_bao'], $data['noi_dung'], $data['da_doc']);
        $res = $stmt->execute();
        if ($res) return $this->conn->insert_id;
        return false;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function markRead($id) {
        $sql = "UPDATE {$this->table} SET da_doc = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }

    public function searchByUser($userId, $q = '', $limit=50, $offset=0) {
        $like = "%{$q}%";
        $sql = "SELECT * FROM {$this->table} WHERE id_nguoi_dung = ? AND noi_dung LIKE ? ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isii", $userId, $like, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function all($limit=100, $offset=0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii",$limit,$offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
