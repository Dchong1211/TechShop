<?php

class Cart {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Lấy toàn bộ giỏ hàng của user
    public function getCartByUser($userId) {
        $sql = "SELECT c.*, p.name, p.price, p.sale_price, p.thumbnail 
                FROM cart_items c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Thêm sản phẩm vào giỏ
    public function addToCart($userId, $productId, $quantity) {
        // Kiểm tra sp đã tồn tại trong giỏ chưa
        $sql = "SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            // Cộng thêm số lượng
            $newQty = $existing['quantity'] + $quantity;
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newQty, $existing['id']);
            return $stmt->execute();
        }

        // Thêm mới
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        return $stmt->execute();
    }

    // Update số lượng
    public function updateQuantity($userId, $productId, $quantity) {
        $sql = "UPDATE cart_items 
                SET quantity = ? 
                WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $userId, $productId);
        return $stmt->execute();
    }

    // Xóa 1 item
    public function removeItem($userId, $productId) {
        $sql = "DELETE FROM cart_items 
                WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    // Xóa toàn bộ giỏ
    public function clearCart($userId) {
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>