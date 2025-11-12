<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm | Admin Panel</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/products.css">
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="products.php" class="active">Quản lý Sản phẩm</a>
        <a href="orders.php">Quản lý Đơn hàng</a>
        <a href="users.php">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>📦 Quản lý Sản phẩm</h1>
        </header>

        <div class="top-actions">
            <a href="products.php?action=add" class="btn btn-primary">Thêm Sản phẩm Mới</a>
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button class="btn btn-search">Tìm</button>
            </div>
        </div>

        <div class="product-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>102</td>
                        <td>Smartphone Samsung S22</td>
                        <td>Điện thoại</td>
                        <td>12.500.000 VNĐ</td>
                        <td>0</td>
                        <td><span class="status-inactive">Hết hàng</span></td>
                        <td class="action-buttons">
                            <a href="edit-products.php?action=edit&id=102" class="btn btn-edit">Sửa</a>
                            <form method="POST" action="products.php" style="display:inline;">
                                <input type="hidden" name="product_id" value="102">
                                <button type="submit" name="action" value="delete" class="btn btn-delete">Xóa</button>
                            </form>
                            <a href="add-products.php?action=edit&id=102" class="btn btn-edit">Thêm</a>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>

        <div class="pagination">
            <a href="#" class="page-link disabled">❮ Trước</a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link">Sau ❯</a>
        </div>

    </div>

</body>
</html>