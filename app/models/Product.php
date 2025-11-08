<?php
//Dừng chương trình nếu lỗi nên sử dụng require_once thay vì include
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Lấy toàn bộ sản phẩm
    public function getAll() {
        $query = "SELECT * FROM products";
        $result = $this->conn->query($query);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Lấy sản phẩm theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        return $product;
    }
}
?>
