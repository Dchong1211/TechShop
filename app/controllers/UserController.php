<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../helpers/pagination.php';

class CustomerController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    // Lấy danh sách user cho client (nếu cần)
    public function list() {
        return [
            "success" => true,
            "data"    => $this->model->getAll()
        ];
    }

    // Lấy chi tiết 1 user theo id
    public function detail($id) {
        $user = $this->model->getById($id);

        return $user
            ? ["success" => true, "data" => $user]
            : ["success" => false, "message" => "Không tìm thấy người dùng!"];
    }

    // Admin: danh sách user + search + phân trang
    public function adminList($page = 1, $limit = 10, $search = "") {
        requireAdmin();

        $search = $this->model->conn->real_escape_string($search);

        // tuỳ bro tên bảng / cột, mình giả sử:
        // bảng nguoi_dung: id, ho_ten, email, avatar, vai_tro, trang_thai, email_verified, ngay_tao,...
        $sql = "
            SELECT *
            FROM nguoi_dung
            WHERE ho_ten LIKE '%$search%'
               OR email  LIKE '%$search%'
            ORDER BY id DESC
        ";

        $result = Pagination::query($this->model->conn, $sql, $page, $limit);

        return [
            "success" => true,
            "meta"    => $result["meta"],
            "data"    => $result["data"]
        ];
    }

    // Admin: tạo nhanh user (nếu bro muốn tạo từ admin)
    public function create() {
        requireAdmin();
        CSRF::requireToken();

        $ho_ten  = trim($_POST['ho_ten'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $mat_khau = $_POST['mat_khau'] ?? '';
        $avatar  = trim($_POST['avatar'] ?? '');
        $vai_tro = $_POST['vai_tro'] ?? 'khach';
        $trang_thai = isset($_POST['trang_thai']) ? intval($_POST['trang_thai']) : 1;

        if ($ho_ten === '' || $email === '' || $mat_khau === '') {
            return [
                "success" => false,
                "message" => "Vui lòng nhập đầy đủ họ tên, email và mật khẩu!"
            ];
        }

        $hashed = password_hash($mat_khau, PASSWORD_DEFAULT);

        $ok = $this->model->create(
            $ho_ten,
            $email,
            $hashed,
            $avatar,
            $vai_tro,
            $trang_thai
        );

        return $ok
            ? ["success" => true, "message" => "Tạo người dùng thành công!"]
            : ["success" => false, "message" => "Tạo người dùng thất bại!"];
    }

    // Admin: cập nhật user (form của bro đang gọi cái này)
    public function update() {
        requireAdmin();
        CSRF::requireToken();

        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            return [
                "success" => false,
                "message" => "Thiếu ID người dùng!"
            ];
        }

        $old = $this->model->getById($id);
        if (!$old) {
            return [
                "success" => false,
                "message" => "Người dùng không tồn tại!"
            ];
        }

        $ho_ten  = trim($_POST['ho_ten'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $avatar  = trim($_POST['avatar'] ?? '');
        $vai_tro = $_POST['vai_tro'] ?? $old['vai_tro'];
        $trang_thai = isset($_POST['trang_thai']) ? intval($_POST['trang_thai']) : $old['trang_thai'];
        $mat_khau = $_POST['mat_khau'] ?? '';

        if ($avatar === '') {
            $avatar = $old['avatar']; // fallback avatar cũ
        }

        if ($ho_ten === '' || $email === '') {
            return [
                "success" => false,
                "message" => "Họ tên và email không được để trống!"
            ];
        }

        // Nếu có nhập mật khẩu mới thì hash, còn không thì truyền null cho model tự bỏ qua
        $hashedPassword = null;
        if ($mat_khau !== '') {
            $hashedPassword = password_hash($mat_khau, PASSWORD_DEFAULT);
        }

        // Bro implement hàm update trong Customer model cho khớp chữ ký này
        $ok = $this->model->update(
            $id,
            $ho_ten,
            $email,
            $avatar,
            $vai_tro,
            $trang_thai,
            $hashedPassword  // null = không đổi mật khẩu
        );

        if ($ok) {
            return [
                "success" => true,
                "message" => "Cập nhật người dùng thành công!"
            ];
        }

        return [
            "success" => false,
            "message" => "Cập nhật người dùng thất bại!"
        ];
    }

    // Admin: xóa user
    public function delete() {
        requireAdmin();
        CSRF::requireToken();

        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            return [
                "success" => false,
                "message" => "Thiếu ID người dùng!"
            ];
        }

        $ok = $this->model->delete($id);

        return $ok
            ? ["success" => true, "message" => "Xóa người dùng thành công!"]
            : ["success" => false, "message" => "Xóa người dùng thất bại!"];
    }
}
