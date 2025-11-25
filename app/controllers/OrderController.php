<?php
    class OrderController {
        private $conn;
        private $orderModel;
        private $itemModel;


        public function __construct($conn) {
            $this->conn = $conn;
            $this->orderModel = new OrderModel($conn);
            $this->itemModel = new OrderItemModel($conn);
        }


        public function index() {
            
        }


        public function show($id) {
            return [
            "order" => $this->orderModel->getOrderById($id),
            "items" => $this->itemModel->getItemsByOrder($id)
            ];
        }


        public function store($user_id, $address, $total_price, $payment_method, $items) {
            $order_id = $this->orderModel->createOrder($user_id, $address, $total_price, $payment_method);


            if (!$order_id) return false;


            foreach ($items as $item) {
                $this->itemModel->addItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
            }


            return $order_id;
        }

        public function update($id, $address, $total_price, $payment_method, $status) {
            return $this->orderModel->updateOrder($id, $address, $total_price, $payment_method, $status);
        }


        public function delete($id) {
            return $this->orderModel->deleteOrder($id);
        }
    }
?>