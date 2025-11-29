<?php

class CategoryController {
    private $model;

    public function __construct()
    {
        requireAdmin();
        $this->model = new Category();
    }

    public function index()
    {
        $data = $this->model->all();
        $this->json($data);
    }

    public function create()
    {
        CSRF::requireToken();
        $id = $this->model->create($_POST['ten_dm'], $_POST['mo_ta'], intval($_POST['trang_thai']));
        header("Location: /admin/categories.php?created=" . ($id?1:0));
    }

    public function edit()
    {
        CSRF::requireToken();
        $ok = $this->model->update(
            intval($_POST['id']),
            $_POST['ten_dm'],
            $_POST['mo_ta'],
            intval($_POST['trang_thai'])
        );
        header("Location: /admin/categories.php?updated=" . ($ok?1:0));
    }

    public function delete()
    {
        CSRF::requireToken();
        $ok = $this->model->delete(intval($_POST['id']));
        header("Location: /admin/categories.php?deleted=" . ($ok?1:0));
    }

    private function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
