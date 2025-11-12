<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Trang Quản Trị</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css">
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/index.css">
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php" class="active">Dashboard</a>
        <a href="products.php">Quản lý Sản phẩm</a>
        <a href="orders.php">Quản lý Đơn hàng</a>
        <a href="users.php">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Chào mừng trở lại, Admin!</h1>
        </header>

        <section class="stats-cards">
            <div class="card">
                <h3>Đơn Hàng Mới (24h)</h3>
                <p>12</p>
                <a href="">Xem chi tiết</a>
            </div>

            <div class="card">
                <h3>Tổng Doanh Thu</h3>
                <p>50 củ VNĐ</p>
                <span>Tăng n% so với tháng trước</span>
            </div>

            <div class="card">
                <h3>Sản Phẩm Cần Nhập</h3>
                <p>n</p>
                <a href="">Xem kho</a>
            </div>

            <div class="card">
                <h3>Tổng Tài Khoản</h3>
                <p>6969</p>
                <span>Người dùng mới: 69</span>
            </div>
        </section>

        <hr>

        <section class="reports">
            <h2>Báo Cáo Doanh Thu 7 Ngày Gần Nhất</h2>
            <div style="height: 300px; background-color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <p style="text-align: center; color: #7f8c8d;">[Vị trí đặt Biểu đồ Doanh thu/Truy cập]</p>
            </div>
        </section>

        <hr>

        <section class="quick-actions">
            <a href="products.php?action=add" class="action-link">Thêm Sản Phẩm Mới</a>
            <a href="orders.php?status=pending" class="action-link">Xử Lý Đơn Hàng Chờ</a>
            <a href="users.php?role=admin" class="action-link">Quản Lý Quyền Admin</a>
        </section>

    </div>

</body>
</html>