<?php
//Dừng chương trình nếu lỗi nên sử dụng require_once thay vì include
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Lấy toàn bộ danh mục
    public function getAll() {
        $query = "SELECT * FROM categories";
        $result = $this->conn->query($query);

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }

    // Lấy danh mục theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();

        return $category;
    }
}
?>
