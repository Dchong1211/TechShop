<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <base href="/TechShop/">
    <link rel="stylesheet" href="public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="public/assets/css/cssAdmin/orders.css"> 
</head>
<body>

    <div class="sidebar">
        <h2>Tech Shop</h2>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Quản lý Sản phẩm</a>
        <a href="orders.php" class="active">Quản lý Đơn hàng</a>
        <a href="users.php">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Quản lý Đơn hàng</h1>
        </header>

        <div class="top-actions filter-actions">
            <div class="filter-group">
                <label for="status-filter">Lọc theo Trạng thái:</label>
                <select id="status-filter">
                    <option value="">Tất cả</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đang đóng gói</option>
                    <option value="shipping">Đang giao hàng</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm theo Mã đơn hàng / Khách hàng...">
                <button class="btn btn-search">Tìm</button>
            </div>
        </div>

        <div class="order-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#2024004</td>
                        <td>Phạm Thị D</td>
                        <td>01/11/2025</td>
                        <td>5.000.000 VNĐ</td>
                        <td>Chuyển khoản</td>
                        <td><span class="status status-cancelled">Đã hủy</span></td>
                        <td class="action-buttons">
                            <a href="order_detail.php?id=2024004" class="btn btn-detail">Xem chi tiết</a>
                            <button class="btn btn-delete">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script src="public/assets/js/admin.js"></script>

</body>
</html>