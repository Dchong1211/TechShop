<?php

require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

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
            "SELECT * FROM nguoi_dung WHERE ho_ten LIKE ? OR email LIKE ?"
        );
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, avatar, vai_tro, trang_thai)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi",
            $data["ho_ten"],
            $data["email"],
            $data["mat_khau"],
            $data["avatar"],
            $data["vai_tro"],
            $data["trang_thai"]
        );
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;  

        $sql = "UPDATE nguoi_dung SET " . implode(", ", $fields) . " WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $types = str_repeat("s", count($values) - 1) . "i";

        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
