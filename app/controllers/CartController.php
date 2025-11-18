<?php

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../helpers/auth.php';

class CartController {
    private $model;
    private $user_id;

    public function __construct() {
        $this->model = new Cart();

        if (isset($_SESSION['user'])) {
            $this->user_id = $_SESSION['user']['id'];
        }
    }

    /* Chặn admin */
    private function blockAdmin() {
        if (!isset($_SESSION['user'])) {
            return ["success" => false, "message" => "Bạn chưa đăng nhập!"];
        }

        if ($_SESSION['user']['role'] === 'admin') {
            return ["success" => false, "message" => "Admin không thể sử dụng giỏ hàng!"];
        }

        return null;
    }

    /* -------------------------------------------------
        GET /api/cart – Lấy toàn bộ giỏ hàng
    --------------------------------------------------*/
    public function getCart() {
        if ($block = $this->blockAdmin()) return $block;

        $items = $this->model->getUserCart($this->user_id);

        return [
            "success" => true,
            "cart" => $items
        ];
    }

    /* -------------------------------------------------
        POST /api/cart/add – Thêm vào giỏ
    --------------------------------------------------*/
    public function add() {
        if ($block = $this->blockAdmin()) return $block;

        $product_id = $_POST["product_id"] ?? 0;
        $quantity = $_POST["quantity"] ?? 1;

        if ($quantity < 1) {
            return ["success" => false, "message" => "Số lượng không hợp lệ!"];
        }

        if (!$this->model->checkProductExists($product_id)) {
            return ["success" => false, "message" => "Sản phẩm không tồn tại!"];
        }

        // Kiểm tra item đã có trong giỏ chưa
        $exists = $this->model->findCartItem($this->user_id, $product_id);

        if ($exists) {
            $new_qty = $exists["quantity"] + $quantity;
            $this->model->updateItem($exists["id"], $this->user_id, $new_qty);
        } else {
            $this->model->insertItem($this->user_id, $product_id, $quantity);
        }

        return ["success" => true, "message" => "Đã thêm vào giỏ hàng!"];
    }

    /* -------------------------------------------------
        POST /api/cart/update – Cập nhật số lượng
    --------------------------------------------------*/
    public function update() {
        if ($block = $this->blockAdmin()) return $block;

        $cart_id = $_POST["cart_id"] ?? 0;
        $quantity = $_POST["quantity"] ?? 0;

        if ($quantity < 1) {
            return ["success" => false, "message" => "Số lượng không hợp lệ!"];
        }

        $ok = $this->model->updateItem($cart_id, $this->user_id, $quantity);

        if (!$ok) {
            return ["success" => false, "message" => "Không thể cập nhật số lượng!"];
        }

        return ["success" => true, "message" => "Đã cập nhật giỏ hàng!"];
    }

    /* -------------------------------------------------
        POST /api/cart/remove – Xóa 1 item
    --------------------------------------------------*/
    public function remove() {
        if ($block = $this->blockAdmin()) return $block;

        $cart_id = $_POST["cart_id"] ?? 0;
        $this->model->deleteItem($cart_id, $this->user_id);

        return ["success" => true, "message" => "Đã xóa sản phẩm!"];
    }

    /* -------------------------------------------------
        POST /api/cart/clear – Xóa toàn bộ giỏ
    --------------------------------------------------*/
    public function clear() {
        if ($block = $this->blockAdmin()) return $block;

        $this->model->clearCart($this->user_id);

        return ["success" => true, "message" => "Đã xóa toàn bộ giỏ hàng!"];
    }
}
