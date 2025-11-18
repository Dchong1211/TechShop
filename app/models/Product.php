<?php

require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Lấy danh sách sản phẩm
    public function getAll() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tìm sản phẩm theo tên
    public function search($keyword) {
        $k = "%{$keyword}%";
        $sql = "SELECT * FROM products WHERE name LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lọc theo category
    public function getByCategory($cate_id) {
        $sql = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cate_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Thêm sản phẩm
    public function create($name, $desc, $price, $image, $cate) {
        $sql = "INSERT INTO products(name,description,price,image,category_id)
                VALUES (?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $cate);
        return $stmt->execute();
    }

    // Sửa sản phẩm
    public function update($id, $name, $desc, $price, $image, $cate) {
        $sql = "UPDATE products SET name=?,description=?,price=?,image=?,category_id=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdsii", $name, $desc, $price, $image, $cate, $id);
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function delete($id) {
        $sql = "DELETE FROM products WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

