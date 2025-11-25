<?php
include __DIR__ . "/../models/CustomerModel.php";

class CustomerController {
    private $model;
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        $this->model = new CustomerModel($conn);
    }

    // -------- INDEX --------
    public function index(){
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $keyword = $_GET['keyword'] ?? "";

        $offset = ($page - 1) * $limit;

        $users = $this->model->getAll($limit, $offset, $keyword);

        $total_records = $this->model->countAll($keyword);
        $total_pages = ceil($total_records / $limit);

        include __DIR__ . "/../public/admin/users.php";
    }

    // -------- ADD --------
    public function add(){
        if (isset($_POST['submit'])){
            $this->model->insert($_POST);
            header("Location: ?controller=customer&action=index");
            exit;
        }

        include __DIR__ . "/../public/admin/add_users.php";
    }

    // -------- EDIT --------
    public function edit(){
        $id = $_GET['id'] ?? 0;
        $user = $this->model->getById($id);

        if (isset($_POST['submit'])){
            $this->model->update($id, $_POST);
            header("Location: ?controller=customer&action=index");
            exit;
        }

        include __DIR__ . "/../public/admin/edit_users.php";
    }

    // -------- DELETE --------
    public function delete(){
        $id = $_GET['id'] ?? 0;
        $this->model->delete($id);
        header("Location: ?controller=customer&action=index");
        exit;
    }
}
