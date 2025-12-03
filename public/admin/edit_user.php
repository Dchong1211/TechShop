<?php
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';

$csrf = CSRF::token();
requireAdmin();

$id = $_GET['id'] ?? 0;


$controller = new CustomerController();
$userData = $controller->detail($id); // Bạn cần tạo method detail($id) trong UserController

if (!$userData['success']) {
    die("<h2 style='color:red; text-align:center;'>Không tìm thấy người dùng!</h2>");
}

$user = $userData['data'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .avatar-upload-container { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .avatar-preview { width: 100px; height: 100px; border-radius: 50%; border: 3px solid #ddd; overflow: hidden; background: #f0f0f0; flex-shrink: 0; }
        .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-input-group { flex-grow: 1; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .right-actions { display: flex; gap: 10px; }
        .status-verified { color: green; font-weight: bold; }
        .status-unverified { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="app-wrapper">
    
    <?php 
    $active_page = 'users'; 
    include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
    ?>

    <main class="main-content">
        <div class="card">
            <form id="updateForm" method="POST" class="form-group-wrapper">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="card-header">
                    <h5 class="card-title">Chỉnh sửa Người dùng: <?= htmlspecialchars($user['ho_ten']) ?> (ID: <?= $user['id'] ?>)</h5>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Avatar URL</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview">
                                <img id="avatarPreview" src="<?= htmlspecialchars($user['avatar'] ?? 'https://via.placeholder.com/150') ?>" alt="Avatar">
                            </div>
                            <div class="avatar-input-group">
                                <input type="text" name="avatar" id="avatarInput" class="form-control" 
                                       placeholder="Dán link avatar..." 
                                       value="<?= htmlspecialchars($user['avatar'] ?? '') ?>" 
                                       oninput="previewAvatar(this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="ho_ten" value="<?= htmlspecialchars($user['ho_ten']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="mat_khau" placeholder="Để trống nếu không muốn thay đổi" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Ngày đăng ký</label>
                        <input type="text" value="<?= date('d/m/Y H:i', strtotime($user['ngay_tao'])) ?>" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email xác minh</label>
                        <span class="<?= $user['email_verified'] ? 'status-verified' : 'status-unverified' ?>">
                            <?= $user['email_verified'] ? 'Đã xác minh' : 'Chưa xác minh' ?>
                        </span>
                    </div>

                    <div class="form-group row-group">
                        <div class="col-6">
                            <label>Vai trò</label>
                            <select name="vai_tro" class="form-control">
                                <option value="khach" <?= $user['vai_tro']=='khach'?'selected':'' ?>>Khách hàng</option>
                                <option value="admin" <?= $user['vai_tro']=='admin'?'selected':'' ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Trạng thái</label>
                            <select name="trang_thai" class="form-control">
                                <option value="1" <?= $user['trang_thai']==1?'selected':'' ?>>Hoạt động</option>
                                <option value="0" <?= $user['trang_thai']==0?'selected':'' ?>>Đã khóa</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/public/admin/users" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                    <div class="right-actions">
                        <button type="button" class="btn btn-delete" onclick="confirmDelete(<?= $user['id'] ?>)">
                            <i class="bi bi-trash"></i> Xóa tài khoản
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu Thay đổi
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </main>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function previewAvatar(url) {
    const preview = document.getElementById('avatarPreview');
    preview.src = url && url.trim() ? url : 'https://via.placeholder.com/150';
}

function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
        const csrf = document.querySelector('input[name="csrf_token"]').value;
        const formData = new FormData();
        formData.append('id', id);
        formData.append('csrf_token', csrf);

        fetch('/TechShop/public/admin/users/delete', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) window.location.href = '/TechShop/public/admin/users';
            else alert('Lỗi: ' + data.message);
        })
        .catch(err => alert('Lỗi kết nối server!'));
    }
}
</script>

</body>
</html>
