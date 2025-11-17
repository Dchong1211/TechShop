<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $conn;
    private $cart;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->cart = new Cart($this->conn);
    }

    private function getCurrentUserId() {
        return $_SESSION['user']['id'] ?? null;
    }

    public function getCart() {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập!'];
        }

        $items = $this->cart->getCartByUser($userId);
        return ['success' => true, 'data' => $items];
    }

    public function add() {
        $userId = $this->getCurrentUserId();
        if (!$userId)
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập!'];

        $productId = intval($_POST['product_id'] ?? 0);
        $quantity  = intval($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity <= 0) {
            return ['success' => false, 'message' => 'Dữ liệu không hợp lệ!'];
        }

        $ok = $this->cart->addToCart($userId, $productId, $quantity);
        return ['success' => $ok];
    }

    public function update() {
        $userId = $this->getCurrentUserId();
        if (!$userId)
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập!'];

        $productId = intval($_POST['product_id'] ?? 0);
        $quantity  = intval($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity <= 0) {
            return ['success' => false, 'message' => 'Dữ liệu không hợp lệ!'];
        }

        $ok = $this->cart->updateQuantity($userId, $productId, $quantity);
        return ['success' => $ok];
    }

    public function remove() {
        $userId = $this->getCurrentUserId();
        if (!$userId)
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập!'];

        $productId = intval($_POST['product_id'] ?? 0);

        if ($productId <= 0) {
            return ['success' => false, 'message' => 'Dữ liệu không hợp lệ!'];
        }

        $ok = $this->cart->removeItem($userId, $productId);
        return ['success' => $ok];
    }

    public function clear() {
        $userId = $this->getCurrentUserId();
        if (!$userId)
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập!'];

        $ok = $this->cart->clearCart($userId);
        return ['success' => $ok];
    }
}
