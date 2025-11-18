<?php

require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function checkProductExists($product_id) {
        $sql = "SELECT id FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserCart($user_id) {
        $sql = "SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.name, p.price, p.image
                FROM cart_items c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findCartItem($user_id, $product_id) {
        $sql = "SELECT id AS cart_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertItem($user_id, $product_id, $quantity) {
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("iii", $user_id, $product_id, $quantity) && $stmt->execute();
    }

    public function updateItem($cart_id, $user_id, $quantity) {
        $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("iii", $quantity, $cart_id, $user_id) && $stmt->execute();
    }

    public function deleteItem($cart_id, $user_id) {
        $sql = "DELETE FROM cart_items WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("ii", $cart_id, $user_id) && $stmt->execute();
    }

    public function clearCart($user_id) {
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("i", $user_id) && $stmt->execute();
    }
}
