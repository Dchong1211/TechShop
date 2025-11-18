<?php

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../helpers/auth.php';

class CartController {
    private $model;
    private $user_id;

    public function __construct() {
        $this->model = new Cart();
        $this->user_id = $_SESSION['user']['id'] ?? null;
    }

    private function blockAdmin() {
        if (!$this->user_id) {
            return ["success" => false, "message" => "Bạn chưa đăng nhập!"];
        }

        if ($_SESSION['user']['role'] === 'admin') {
            return ["success" => false, "message" => "Admin không thể sử dụng giỏ hàng!"];
        }

        return null;
    }

    public function getCart() {
        if ($b = $this->blockAdmin()) return $b;
        return ["success" => true, "cart" => $this->model->getUserCart($this->user_id)];
    }

    public function add() {
        if ($b = $this->blockAdmin()) return $b;

        $product_id = $_POST['product_id'] ?? 0;
        $quantity   = $_POST['quantity'] ?? 1;

        if ($quantity < 1) return ["success" => false, "message" => "Số lượng không hợp lệ"];

        if (!$this->model->checkProductExists($product_id)) {
            return ["success" => false, "message" => "Sản phẩm không tồn tại!"];
        }

        $exists = $this->model->findCartItem($this->user_id, $product_id);

        if ($exists) {
            $newQty = $exists["quantity"] + $quantity;
            $this->model->updateItem($exists["cart_id"], $this->user_id, $newQty);
        } else {
            $this->model->insertItem($this->user_id, $product_id, $quantity);
        }

        return ["success" => true, "message" => "Đã thêm vào giỏ hàng!"];
    }

    public function update() {
        if ($b = $this->blockAdmin()) return $b;

        $cart_id  = $_POST['cart_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 0;

        if ($quantity < 1) return ["success" => false, "message" => "Số lượng không hợp lệ"];

        $ok = $this->model->updateItem($cart_id, $this->user_id, $quantity);

        return $ok
            ? ["success" => true, "message" => "Đã cập nhật!"]
            : ["success" => false, "message" => "Không cập nhật được!"];
    }

    public function remove() {
        if ($b = $this->blockAdmin()) return $b;

        $cart_id = $_POST['cart_id'] ?? 0;
        $this->model->deleteItem($cart_id, $this->user_id);

        return ["success" => true, "message" => "Đã xóa sản phẩm!"];
    }

    public function clear() {
        if ($b = $this->blockAdmin()) return $b;

        $this->model->clearCart($this->user_id);
        return ["success" => true, "message" => "Đã xóa toàn bộ giỏ hàng!"];
    }
}
