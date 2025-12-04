<?php

require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    /* Lấy tất cả khách (hoặc bạn đổi thành getAllUsers nếu muốn) */
    public function getAllCustomers() {
        $sql = "SELECT * FROM nguoi_dung ORDER BY id ASC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function search($query) {
        $like = "%$query%";
        $stmt = $this->conn->prepare(
            "SELECT * FROM nguoi_dung 
             WHERE (ho_ten LIKE ? OR email LIKE ?)"
        );
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO nguoi_dung 
                (ho_ten, email, mat_khau, avatar, vai_tro, trang_thai, email_verified) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $email_verified = $data['email_verified'] ?? 0;

        $stmt->bind_param(
            "sssssis",
            $data['ho_ten'],
            $data['email'],
            $data['mat_khau'],
            $data['avatar'],
            $data['vai_tro'],
            $data['trang_thai'],
            $email_verified
        );

        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        $types  = "";

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
            $types   .= is_int($value) ? "i" : "s";
        }

        $values[] = $id;
        $types   .= "i";

        $sql = "UPDATE nguoi_dung SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    public function changeStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE nguoi_dung SET trang_thai = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
