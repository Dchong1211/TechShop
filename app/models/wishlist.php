<?php
class WishlistModel {
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function add($userId, $productId, $detailKey = null){
        $sql = "INSERT INTO wishlist (user_id, product_id, detail_key) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE detail_key = VALUES(detail_key), created_at = CURRENT_TIMESTAMP";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $userId, $productId, $detailKey);
        return $stmt->execute();
    }

    public function remove($userId, $productId){
        $sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    public function getByUser($userId, $limit = 50, $offset = 0){
        $sql = "SELECT w.*, p.* FROM wishlist w
                JOIN product p ON p.Id = w.product_id
                WHERE w.user_id = ? ORDER BY w.created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsersByProduct($productId){
        $sql = "SELECT u.id, u.name, u.email FROM wishlist w
                JOIN users u ON u.id = w.user_id
                WHERE w.product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
