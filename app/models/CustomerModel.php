<?php
class CustomerModel {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // Lấy danh sách user + tìm kiếm + phân trang
    public function getAll($limit, $offset, $keyword = ""){
        $keyword = $this->conn->real_escape_string($keyword);

        $sql = "SELECT * FROM users 
                WHERE name LIKE '%$keyword%' OR email LIKE '%$keyword%'
                ORDER BY id DESC
                LIMIT $offset, $limit";

        return $this->conn->query($sql);
    }

    // Đếm tổng user theo keyword
    public function countAll($keyword = ""){
        $keyword = $this->conn->real_escape_string($keyword);

        $sql = "SELECT COUNT(*) AS total 
                FROM users 
                WHERE name LIKE '%$keyword%' OR email LIKE '%$keyword%'";

        return $this->conn->query($sql)->fetch_assoc()['total'];
    }

    // Lấy 1 user theo ID
    public function getById($id){
        return $this->conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
    }

    // Thêm user
    public function insert($data){
        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $role = $data['role'];

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', '$role')";

        return $this->conn->query($sql);
    }

    // Sửa user
    public function update($id, $data){
        $name = $data['name'];
        $email = $data['email'];
        $role = $data['role'];
        $status = $data['status'];

        $sql = "UPDATE users SET 
                name='$name',
                email='$email',
                role='$role',
                status='$status'
                WHERE id=$id";

        return $this->conn->query($sql);
    }

    // Xóa user
    public function delete($id){
        return $this->conn->query("DELETE FROM users WHERE id = $id");
    }
}
