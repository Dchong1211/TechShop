<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/forms.css">
    <style>
        .password-note {
            font-size: 0.9rem;
            color: #95a5a6;
            margin-top: 5px;
        }
        .form-group.row-group {
            display: flex;
            gap: 20px;
        }
        .form-group.row-group .col-6 {
            flex: 1;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Tech Shop</h2>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Quản lý Sản phẩm</a>
        <a href="orders.php">Quản lý Đơn hàng</a>
        <a href="users.php" class="active">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Chỉnh sửa Người dùng</h1>
            <p>ID Tài khoản: 3 - Lê Văn C</p>
        </header>

        <div class="form-container">
            <h2 class="form-title">Cập nhật thông tin chi tiết</h2>
            
            <form action="users.php" method="POST" class="form-group-wrapper">
                <input type="hidden" name="user_id" value="3">

                <div class="form-group">
                    <label for="full_name">Họ và Tên</label>
                    <input type="text" id="full_name" name="full_name" value="Lê Văn C" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="levanc@editor.com" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Điện thoại</label>
                    <input type="text" id="phone" name="phone" value="0901-234-567">
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" id="password" name="password" placeholder="Để trống nếu không muốn thay đổi">
                    <p class="password-note">Lưu ý: Nhập mật khẩu mới chỉ khi bạn muốn thay đổi mật khẩu hiện tại.</p>
                </div>
                
                <h3 class="sub-section-title">Phân quyền & Trạng thái</h3>
                
                <div class="form-group row-group">
                    <div class="col-6">
                        <label for="role">Vai trò (Role)</label>
                        <select id="role" name="role" required>
                            <option value="user">Khách hàng (User)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="status">Trạng thái Tài khoản</label>
                        <select id="status" name="status" required>
                            <option value="active">Hoạt động</option>
                            <option value="locked" selected>Đã khóa</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Lưu Thay đổi</button>
                    <a href="users.php" class="btn btn-cancel">Hủy bỏ</a>
                    <button type="button" class="btn btn-delete" style="float: right;">Xóa Tài khoản</button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>