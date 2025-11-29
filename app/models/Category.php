<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    private $table = 'danh_muc';

    // Nhận $conn từ database.php
    public function __construct($conn = null) {
        if ($conn !== null) {
            $this->conn = $conn;
        } else {
            // Lấy biến $conn từ database.php
            global $conn;
            $this->conn = $conn;
        }
    }

    // CREATE
    public function create($ten_dm, $mo_ta = null, $trang_thai = 1) {
        $sql = "INSERT INTO {$this->table} (ten_dm, mo_ta, trang_thai)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $ten_dm, $mo_ta, $trang_thai);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // READ ONE
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE
    public function update($id, $ten_dm, $mo_ta, $trang_thai) {
        $sql = "UPDATE {$this->table}
                SET ten_dm = ?, mo_ta = ?, trang_thai = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $ten_dm, $mo_ta, $trang_thai, $id);

        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // SEARCH
    public function search($q, $limit = 50, $offset = 0) {
        $like = "%{$q}%";
        $sql = "SELECT * FROM {$this->table}
                WHERE ten_dm LIKE ? OR mo_ta LIKE ?
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // GET ALL
    public function all($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM {$this->table}
                ORDER BY id DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
