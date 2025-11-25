<?php
    class UserAddressModel {
        private $conn;
        public function __construct($conn) {
        $this->conn = $conn;
        }


        public function addAddress($user_id, $full_name, $phone, $address, $is_default) {
            if ($is_default) {
                $this->conn->query("UPDATE user_addresses SET is_default=0 WHERE user_id=".$user_id);
            }
            $sql = "INSERT INTO user_addresses (user_id, full_name, phone, address, is_default) VALUES (?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isssi", $user_id, $full_name, $phone, $address, $is_default);
            return $stmt->execute() ? $this->conn->insert_id : false;
        }


        public function getAddressesByUser($user_id) {
            $sql = "SELECT * FROM user_addresses WHERE user_id=? ORDER BY is_default DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            return $stmt->get_result();
        }


        public function getAddressById($id) {
            $sql = "SELECT * FROM user_addresses WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }


        public function updateAddress($id, $full_name, $phone, $address, $is_default, $user_id) {
            if ($is_default) {
                $this->conn->query("UPDATE user_addresses SET is_default=0 WHERE user_id=".$user_id);
            }
            $sql = "UPDATE user_addresses SET full_name=?, phone=?, address=?, is_default=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssii", $full_name, $phone, $address, $is_default, $id);
            return $stmt->execute();
        }


        public function deleteAddress($id) {
            $sql = "DELETE FROM user_addresses WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
    }
?>