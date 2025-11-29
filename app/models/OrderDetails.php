<?php
require_once __DIR__ . '/../config/database.php';

class OrderDetail {
    private $conn;
    private $table = 'chi_tiet_don_hang';

    public function __construct($conn = null) {
        global $conn as $globalConn;
        $this->conn = $conn ?? $globalConn;
    }

    public function add($data) {
        $sql = "INSERT INTO {$this->table} (id_don_hang, id_san_pham, so_luong, don_gia, gia_von) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii dd",
            $data['id_don_hang'],
            $data['id_san_pham'],
            $data['so_luong'],
            $data['don_gia'],
            $data['gia_von']
        );
        // Correction: PHP requires types string like "iiidd" -> use "iiidd"
        // But to avoid mismatch, let's rebind:
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiidd",
            $data['id_don_hang'],
            $data['id_san_pham'],
            $data['so_luong'],
            $data['don_gia'],
            $data['gia_von']
        );
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

    public function listByOrder($orderId) {
        $sql = "SELECT * FROM {$this->table} WHERE id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET id_san_pham = ?, so_luong = ?, don_gia = ?, gia_von = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiidi",
            $data['id_san_pham'],
            $data['so_luong'],
            $data['don_gia'],
            $data['gia_von'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }

    public function search($q, $limit=50, $offset=0) {
        $like = "%{$q}%";
        // searching by product id or order id or matching ma_chi_tiet not available here — so search numeric fields or join (simple)
        $sql = "SELECT * FROM {$this->table} WHERE CAST(id_san_pham AS CHAR) LIKE ? OR CAST(id_don_hang AS CHAR) LIKE ? LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii",$like,$like,$limit,$offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
