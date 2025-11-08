<?php
//Dừng chương trình nếu lỗi nên sử dụng require_once thay vì include
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Tạo đơn hàng mới
    public function create($user_id, $total_price, $address, $payment_method) {
        $stmt = $this->conn->prepare("
            INSERT INTO orders (user_id, total_price, address, payment_method, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("idss", $user_id, $total_price, $address, $payment_method);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Lấy danh sách đơn hàng của user
    public function getByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
        return $orders;
    }
}
?>
