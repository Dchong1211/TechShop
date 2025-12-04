<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

requireAdmin();
$csrf = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Người dùng</title>
    <meta name="csrf-token" content="<?= $csrf ?>">
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<div class="app-wrapper">
    <?php 
        $active_page = 'users';
        include __DIR__ . '/../includes/Admin/layout_sidebar.php';
    ?>
    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Thêm Người dùng mới</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/TechShop/admin/users/add" class="form-group-wrapper">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <div class="form-group">
                        <label>Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="mat_khau" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Avatar URL</label>
                        <input type="text" name="avatar" class="form-control">
                    </div>
                    <div class="form-group row-group">
                        <div class="col-6">
                            <label>Vai trò</label>
                            <select name="vai_tro" class="form-control">
                                <option value="khach">Khách hàng</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Trạng thái</label>
                            <select name="trang_thai" class="form-control">
                                <option value="1">Hoạt động</option>
                                <option value="0">Đã khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:16px;">
                        <a href="/TechShop/admin/users" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
