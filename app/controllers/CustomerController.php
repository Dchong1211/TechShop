<?php
include "./models/CustomerModel.php";

class CustomerController {
    private $model;
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        $this->model = new CustomerModel($conn);
    }

    // -------- INDEX (LIST + SEARCH + PAGINATION) --------
    public function index(){
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";

        $offset = ($page - 1) * $limit;

        $users = $this->model->getAll($limit, $offset, $keyword);

        $total_records = $this->model->countAll($keyword);
        $total_pages = ceil($total_records / $limit);

        include "./views/customer/index.php";
    }

    // -------- ADD --------
    public function add(){
        if (isset($_POST['submit'])){
            $this->model->insert($_POST);
            header("Location: ?controller=customer&action=index");
        }

        include "./views/customer/form_add.php";
    }

    // -------- EDIT --------
    public function edit(){
        $id = $_GET['id'];
        $user = $this->model->getById($id);

        if (isset($_POST['submit'])){
            $this->model->update($id, $_POST);
            header("Location: ?controller=customer&action=index");
        }

        include "./views/customer/form_edit.php";
    }

    // -------- DELETE --------
    public function delete(){
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: ?controller=customer&action=index");
    }
}
