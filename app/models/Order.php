<?php
    class OrderModel {
        return $this->conn->query($sql);
    }
    // Get single order by ID
    public function getOrderById($id) {
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }
    // Update order
    public function updateOrder($id, $address, $total_price, $payment_method, $status) {
    $sql = "UPDATE orders SET address=?, total_price=?, payment_method=?, status=? WHERE id=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdssi", $address, $total_price, $payment_method, $status, $id);
    return $stmt->execute();
    }
    // Delete order
    public function deleteOrder($id) {
    $sql = "DELETE FROM orders WHERE id=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
    }
?>