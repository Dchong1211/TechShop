<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* CSS cho phần upload ảnh Avatar */
        .avatar-upload-container {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #ddd;
            overflow: hidden;
            background: #f0f0f0;
            flex-shrink: 0;
        }
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-input-group {
            flex-grow: 1;
        }
        
        /* CSS bố cục nút bấm */
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .right-actions {
            display: flex;
            gap: 10px;
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
                <form action="users.php" method="POST" enctype="multipart/form-data" class="form-group-wrapper">
                    <input type="hidden" name="user_id" value="3">
                    
                    <div class="card-header">
                        <h5 class="card-title">Chỉnh sửa Người dùng (ID: 3)</h5>
                    </div>
                    
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label>Ảnh đại diện (Avatar)</label>
                            <div class="avatar-upload-container">
                                <div class="avatar-preview">
                                    <img id="avatarPreview" src="https://via.placeholder.com/150" alt="Avatar">
                                </div>
                                <div class="avatar-input-group">
                                    <input type="file" name="avatar" id="avatarInput" class="form-control" accept="image/*" onchange="previewAvatar()">
                                    <small class="text-muted">Chấp nhận file ảnh: jpg, png, jpeg</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="full_name">Họ và Tên</label>
                            <input type="text" id="full_name" name="full_name" value="Lê Văn C" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="levanc@editor.com" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Điện thoại</label>
                            <input type="text" id="phone" name="phone" value="0901-234-567" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" placeholder="Để trống nếu không muốn thay đổi" class="form-control">
                        </div>
                        
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
                        <a href="users.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                        
                        <div class="right-actions">
                            <button type="button" class="btn btn-delete" onclick="if(confirm('Xóa vĩnh viễn người dùng này?')) { window.location.href='?action=delete&id=3'; }">
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
    
    <script src="/public/assets/js/admin.js"></script>
    <script>
        function previewAvatar() {
            const preview = document.getElementById('avatarPreview');
            const file = document.getElementById('avatarInput').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>