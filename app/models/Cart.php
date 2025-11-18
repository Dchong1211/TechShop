<?php

require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /* ------------------------------------------------------
        Kiểm tra product có tồn tại không
    -------------------------------------------------------*/
    public function checkProductExists($product_id) {
        $sql = "SELECT id FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* ------------------------------------------------------
        Lấy tất cả sản phẩm trong giỏ của user
    -------------------------------------------------------*/
    public function getUserCart($user_id) {
        $sql = "
            SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.name, p.price, p.image
            FROM cart_items c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $rs = $stmt->get_result();
        $data = [];

        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /* ------------------------------------------------------
        Kiểm tra item đã có trong giỏ hay chưa
    -------------------------------------------------------*/
    public function findCartItem($user_id, $product_id) {
        $sql = "SELECT * FROM cart_items WHERE user_id = ? AND product_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* ------------------------------------------------------
        Insert item mới
    -------------------------------------------------------*/
    public function insertItem($user_id, $product_id, $quantity) {
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("iii", $user_id, $product_id, $quantity) && $stmt->execute();
    }

    /* ------------------------------------------------------
        Update quantity
    -------------------------------------------------------*/
    public function updateItem($cart_id, $user_id, $quantity) {
        $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("iii", $quantity, $cart_id, $user_id) && $stmt->execute();
    }

    /* ------------------------------------------------------
        Xóa 1 item
    -------------------------------------------------------*/
    public function deleteItem($cart_id, $user_id) {
        $sql = "DELETE FROM cart_items WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("ii", $cart_id, $user_id) && $stmt->execute();
    }

    /* ------------------------------------------------------
        Clear toàn bộ giỏ
    -------------------------------------------------------*/
    public function clearCart($user_id) {
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->bind_param("i", $user_id) && $stmt->execute();
    }
}
