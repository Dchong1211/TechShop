<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="public/assets/css/cssAdmin/forms.css">
</head>
<body>

    <div class="app-wrapper">
        
        <nav class="top-navbar">
            <div class="navbar-left">
                <a href="index.php" class="navbar-brand">TechShop</a>
                <button class="sidebar-toggle" type="button">☰</button> </div>
            <div class="navbar-search">
                <input type="text" placeholder="Search...">
            </div>
            <div class="navbar-right">
                <button class="theme-toggle" id="theme-toggle" type="button" title="Chuyển đổi Sáng/Tối">
                    <span class="icon-sun">[☀️]</span>
                    <span class="icon-moon">[🌙]</span>
                </button>
                <a href="#" class="nav-icon"><i class="bi bi-bell" style="color: #5e6e82"></i></a>
                <a href="#" class="nav-icon"><i class="bi bi-gear" style="color: #5e6e82"></i></a>
                <a href="#" class="nav-icon user-avatar">[User]</a>
            </div>
        </nav>

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="index.php" class="active">
                            <span class="icon"><i class="bi bi-house" style="color: #5e6e82"></i></span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <span class="icon"><i class="bi bi-box" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <span class="icon"><i class="bi bi-cart" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Đơn hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <span class="icon"><i class="bi bi-people" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Người dùng</span>
                        </a>
                    </li>
                    <li class="sidebar-logout">
                        <a href="login.php">
                            <span class="icon"><i class="bi bi-box-arrow-right" style="color: #5e6e82"></i></span>
                            <span class="title">Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

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