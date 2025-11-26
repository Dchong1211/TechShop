<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
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
                <form action="users.php" method="POST" class="form-group-wrapper">
                    <input type="hidden" name="user_id" value="3">
                    
                    <div class="card-header">
                        <h5 class="card-title">Chỉnh sửa Người dùng (ID: 3 - Lê Văn C)</h5>
                    </div>
                    
                    <div class="card-body">
                        
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
                            <p class="password-note">Lưu ý: Nhập mật khẩu mới chỉ khi bạn muốn thay đổi mật khẩu hiện tại.</p>
                        </div>
                        
                        <h3 class="sub-section-title">Phân quyền & Trạng thái</h3>
                        
                        <div class="form-group row-group">
                            <div class="col-6">
                                <label for="role">Vai trò (Role)</label>
                                <select id="role" name="role" required class="form-control">
                                    <option value="user">Khách hàng (User)</option>
                                    <option value="admin">Quản trị viên (Admin)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="status">Trạng thái Tài khoản</label>
                                <select id="status" name="status" required class="form-control">
                                    <option value="active">Hoạt động</option>
                                    <option value="locked" selected>Đã khóa</option>
                                </select>
                            </div>
                        </div>
                        
                    </div> <div class="card-footer">
                        <button type="button" class="btn btn-delete" style="margin-right: auto;">Xóa Tài khoản</button>
                        
                        <a href="users.php" class="btn btn-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Lưu Thay đổi</button>
                    </div>
                    
                </form>
            </div> </main> 
        
    </div> <script src="/public/assets/js/admin.js"></script>

</body>
</html>