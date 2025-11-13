<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/users.css"> 
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
            <h1>Quản lý Người dùng</h1>
        </header>

        <div class="top-actions filter-actions">
            <a href="add_user.php" class="btn btn-primary">Thêm Người dùng Mới</a>
            
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm theo Tên / Email...">
                <button class="btn btn-search">Tìm</button>
            </div>
        </div>

        <div class="user-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Người dùng</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Ngày đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Nguyễn Văn A</td>
                        <td>nguyenvana@admin.com</td>
                        <td><span class="role role-admin">Admin</span></td>
                        <td>01/01/2024</td>
                        <td><span class="status status-active">Hoạt động</span></td>
                        <td class="action-buttons">
                            <a href="edit_user.php" class="btn btn-edit">Sửa</a>
                            <button class="btn btn-lock">Khóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>