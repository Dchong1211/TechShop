<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/forms.css">
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
            <h1>✍️ Sửa Thông tin Sản phẩm</h1>
        </header>

        <div class="form-container">
            <form action="products.php" method="POST" enctype="multipart/form-data" class="product-form">
                <input type="hidden" name="product_id" value="102">
                
                <div class="form-group">
                    <label for="id">ID Sản phẩm</label>
                    <input type="text" id="id" name="id" value="102" readonly class="readonly">
                </div>
                
                <div class="form-group">
                    <label for="name">Tên Sản phẩm <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required value="Smartphone Samsung S22">
                </div>
                
                <div class="form-group">
                    <label for="category">Danh mục <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="1">Laptop</option>
                        <option value="2" selected>Điện thoại</option>
                        <option value="3">Phụ kiện</option>
                    </select>
                </div>
                
                <div class="form-group row-group">
                    <div class="col-6">
                        <label for="price">Giá bán (VNĐ) <span class="required">*</span></label>
                        <input type="number" id="price" name="price" required min="1000" value="12500000">
                    </div>
                    <div class="col-6">
                        <label for="stock">Số lượng tồn kho <span class="required">*</span></label>
                        <input type="number" id="stock" name="stock" required min="0" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <div class="radio-group">
                        <input type="radio" id="status_active" name="status" value="1" checked>
                        <label for="status_active">Hoạt động</label>
                        <input type="radio" id="status_inactive" name="status" value="0">
                        <label for="status_inactive">Ngừng bán</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description" rows="6">Mô tả chi tiết về điện thoại Samsung S22...</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit-update">Cập nhật Sản phẩm</button>
                    <a href="products.php" class="btn btn-cancel">Quay lại</a>
                </div>
            </form>
        </div>

    </div>

</body>
</html>