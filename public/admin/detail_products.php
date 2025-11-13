<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Sản phẩm </title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/forms.css">
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/products.css">
</head>
<body>

    <div class="sidebar">
        <h2>Tech Shop</h2>
        <a href="index.php">Dashboard</a>
        <a href="products.php" class="active">Quản lý Sản phẩm</a>
        <a href="orders.php">Quản lý Đơn hàng</a>
        <a href="users.php">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>👁️ Chi tiết Sản phẩm</h1>
        </header>

        <div class="detail-container">
            <div class="detail-box">
                <div class="detail-image">
                    <img src="https://placehold.co/400x300/a8d8e0/34495e?text=Samsung+S22" alt="Hình ảnh sản phẩm">
                </div>

                <div class="detail-info">
                    <h2>Smartphone Samsung S22</h2>
                    <p class="status-badge-wrapper">Trạng thái: <span class="status-inactive">Hết hàng</span></p>

                    <table class="detail-table">
                        <tr>
                            <th>ID Sản phẩm</th>
                            <td>102</td>
                        </tr>
                        <tr>
                            <th>Danh mục</th>
                            <td>Điện thoại</td>
                        </tr>
                        <tr>
                            <th>Giá bán</th>
                            <td>12.500.000 VNĐ</td>
                        </tr>
                        <tr>
                            <th>Số lượng tồn</th>
                            <td>0</td>
                        </tr>
                        <tr>
                            <th>Mô tả chi tiết</th>
                            <td>Mô tả chi tiết về điện thoại Samsung S22, thiết kế hiện đại, camera siêu nét, chip hiệu năng cao. Sản phẩm đang tạm thời hết hàng do nhu cầu lớn.</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="edit_products.php?id=102" class="btn btn-edit">Chỉnh sửa thông tin</a>
                <a href="products.php" class="btn btn-cancel">Quay lại danh sách</a>
            </div>
        </div>

    </div>

</body>
</html>