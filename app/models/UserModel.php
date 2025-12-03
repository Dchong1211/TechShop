<?php

require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Lấy tất cả khách hàng
    public function getAllCustomers() {
        $sql = "SELECT * FROM nguoi_dung WHERE vai_tro = 'khach' ORDER BY id ASC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy chi tiết user theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tìm kiếm khách hàng theo tên hoặc email
    public function search($query) {
        $like = "%$query%";
        $stmt = $this->conn->prepare(
            "SELECT * FROM nguoi_dung WHERE (ho_ten LIKE ? OR email LIKE ?) AND vai_tro = 'khach'"
        );
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Tạo user mới
    public function create($data) {
    $sql = "INSERT INTO nguoi_dung (ho_ten, email, password, avatar, vai_tro, trang_thai)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssssi",
        $data['ho_ten'],
        $data['email'],
        $data['password'],
        $data['avatar'],
        $data['vai_tro'],
        $data['trang_thai']
    );
    $stmt->execute();
    return $this->conn->insert_id;
}


    // Cập nhật user
    public function update($id, array $data) {
        $sql = "UPDATE nguoi_dung SET ho_ten=?, email=?, vai_tro=?, trang_thai=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssii",
            $data['ho_ten'],
            $data['email'],
            $data['vai_tro'],
            $data['trang_thai'],
            $id
        );
        return $stmt->execute();
    }

    // Xóa user
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nguoi_dung WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
