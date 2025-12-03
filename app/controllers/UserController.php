<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';

class CustomerController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
        requireLogin();// Chỉ cần đăng nhập, không cần admin
    }

    // Danh sách khách hàng (có thể dùng cho admin hoặc quản lý)
    public function list() {
        return [
            "success" => true,
            "data" => $this->model->getAllCustomers() // giả sử có method này
        ];
    }

    // Xem chi tiết khách hàng
    public function detail($id) {
        $item = $this->model->getById($id);

        return $item
            ? ["success" => true, "data" => $item]
            : ["success" => false, "message" => "Không tìm thấy khách hàng!"];
    }

    // Tìm khách hàng theo email hoặc tên
    public function search($query) {
        return [
            "success" => true,
            "data" => $this->model->search($query)
        ];
    }

    // Hiển thị form thêm user (GET)
    public function createForm() {
        $csrf = CSRF::token();
        include __DIR__ . '/../../public/admin/add_users.php';
    }

    // Xử lý POST thêm user
    public function create() {
        CSRF::requireToken(); // kiểm tra token CSRF

        // Lấy dữ liệu từ form
        $data = [
            'ho_ten' => $_POST['full_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'avatar' => $_POST['avatar'] ?? '',
            'vai_tro' => $_POST['role'] ?? 'user',
            'trang_thai' => $_POST['status'] ?? 1
        ];

        // Hash password nếu có
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Thêm user
        $id = $this->model->create($data);

        if ($id) {
            header("Location: /TechShop/public/admin/add_users.php?created=1&id={$id}");
        } else {
            header("Location: /TechShop/public/admin/add_users.php?created=0");
        }
        exit;
    }


    // Khách hàng tự tạo tài khoản
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return ["success"=>false,"message"=>"Phải dùng POST"];
        CSRF::requireToken();

        $data = [
            'ho_ten'   => $_POST['ho_ten'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'mat_khau' => password_hash($_POST['mat_khau'] ?? '', PASSWORD_BCRYPT),
            'vai_tro'  => 'khach',
            'trang_thai'=> 1
        ];

        $id = $this->model->create($data);
        return $id 
            ? ["success" => true, "message" => "Đăng ký thành công", "id" => $id]
            : ["success" => false, "message" => "Đăng ký thất bại"];
    }

    public function edit_user(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            $id = intval($_POST['id']);
            // ... xử lý cập nhật
        } else {
            $id = intval($_GET['id']);
            $user = $this->model->getById($id);
            // ... hiển thị form edit
        }
    }


    // Khách hàng cập nhật thông tin cá nhân
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return ["success"=>false,"message"=>"Phải dùng POST"];
        CSRF::requireToken();

        $id = $_SESSION['user_id'] ?? 0; // Lấy ID người đang đăng nhập
        if (!$id) return ["success"=>false,"message"=>"Chưa đăng nhập"];

        $data = [
            'ho_ten' => $_POST['ho_ten'] ?? '',
            'email'  => $_POST['email'] ?? ''
        ];

        $ok = $this->model->update($id, $data);
        return $ok
            ? ["success" => true, "message" => "Cập nhật thành công"]
            : ["success" => false, "message" => "Cập nhật thất bại"];
    }

    // Khách hàng xóa tài khoản (tùy chọn)
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return ["success"=>false,"message"=>"Phải dùng POST"];
        CSRF::requireToken();

        $id = $_SESSION['user_id'] ?? 0; // Hoặc admin xóa
        if (!$id) return ["success"=>false,"message"=>"Chưa đăng nhập"];

        $ok = $this->model->delete($id);
        return $ok
            ? ["success" => true, "message" => "Xóa tài khoản thành công"]
            : ["success" => false, "message" => "Xóa thất bại"];
    }
}
