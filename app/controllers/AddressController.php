<?php
    class UserAddressController {
        private $model;


        public function __construct($conn) {
            $this->model = new UserAddressModel($conn);
        }


        public function index($user_id) {
            return $this->model->getAddressesByUser($user_id);
        }


        public function show($id) {
            return $this->model->getAddressById($id);
        }


        public function store($user_id, $full_name, $phone, $address, $is_default) {
            return $this->model->addAddress($user_id, $full_name, $phone, $address, $is_default);
        }


        public function update($id, $user_id, $full_name, $phone, $address, $is_default) {
            return $this->model->updateAddress($id, $full_name, $phone, $address, $is_default, $user_id);
        }


        public function delete($id) {
            return $this->model->deleteAddress($id);
        }
    }
?>