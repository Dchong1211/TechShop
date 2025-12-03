<?php
    require_once __DIR__ . '/../../app/helpers/auth.php';
    require_once __DIR__ . '/../../app/helpers/CSRF.php';
    $csrf = CSRF::token();
    requireAdmin();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người dùng Mới</title>

    <meta name="csrf-token" content="<?= $csrf ?>">

    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .avatar-upload-container {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
        }
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #ccc;
            overflow: hidden;
            background: #f0f0f0;
            flex-shrink: 0;
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        .avatar-input-group {
            flex-grow: 1;
        }
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
            <form action="/TechShop/public/admin/users/add" 
                  method="POST" 
                  class="user-form" 
                  id="addUserForm">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">

                <div class="card-header">
                    <h5 class="card-title">Thêm Người dùng Mới</h5>
                </div>

                <div class="card-body">

                    <!-- Avatar -->
                    <div class="form-group">
                        <label>Link Ảnh đại diện (Avatar URL)</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview">
                                <img id="avatarPreview" src="#" alt="Avatar">
                            </div>
                            <div class="avatar-input-group">
                                <input type="text" name="avatar" id="avatarInput" 
                                       class="form-control" 
                                       placeholder="Dán link ảnh avatar..." 
                                       oninput="previewAvatar(this.value)">
                            </div>
                        </div>
                    </div>

                    <!-- Họ tên -->
                    <div class="form-group">
                        <label for="full_name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" required class="form-control" placeholder="Nhập họ và tên">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required class="form-control" placeholder="Nhập email">
                    </div>

                    <!-- SĐT -->
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại">
                    </div>

                    <!-- Mật khẩu -->
                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required class="form-control" placeholder="Nhập mật khẩu">
                    </div>

                    <!-- Vai trò & Trạng thái -->
                    <div class="form-group row-group">
                        <div class="col-6">
                            <label for="role">Vai trò</label>
                            <select id="role" name="role" required class="form-control">
                                <option value="user">Khách hàng</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="status">Trạng thái</label>
                            <select id="status" name="status" required class="form-control">
                                <option value="active">Hoạt động</option>
                                <option value="locked">Đã khóa</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/public/admin/users" class="btn btn-secondary">Hủy bỏ</a>
                    <button type="button" class="btn btn-primary" onclick="confirmAddUser()">Lưu Người dùng</button>
                </div>

            </form>
        </div>

    </main>

</div>

<script src="/TechShop/public/assets/js/admin.js"></script>
<script>
    // Xem trước avatar
    function previewAvatar(url) {
        const preview = document.getElementById('avatarPreview');
        if(url && url.length > 5) {
            preview.src = url;
            preview.style.display = 'block';
            preview.onerror = function() { this.style.display = 'none'; };
        } else {
            preview.style.display = 'none';
        }
    }

    // Xác nhận Thêm Người dùng
    function confirmAddUser() {
        const form = document.getElementById('addUserForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if(confirm("Bạn có chắc chắn muốn thêm người dùng mới này?")) {
            const btn = form.querySelector('.btn-primary');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Đang xử lý...';
            btn.disabled = true;

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Thêm người dùng thành công!');
                    window.location.href = '/TechShop/public/admin/users';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server!');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    }
</script>

</body>
</html>
