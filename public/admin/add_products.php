<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản phẩm Mới</title>
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
            <h1>Thêm Sản phẩm Mới</h1>
        </header>

        <div class="form-container">
            <form action="products.php" method="POST" enctype="multipart/form-data" class="product-form">
                
                <div class="form-group">
                    <label for="name">Tên Sản phẩm <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required placeholder="Nhập tên sản phẩm">
                </div>
                
                <div class="form-group">
                    <label for="category">Danh mục <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">Chọn danh mục</option>
                        <option value="1">Laptop</option>
                        <option value="2">Điện thoại</option>
                        <option value="3">Phụ kiện</option>
                    </select>
                </div>
                
                <div class="form-group row-group">
                    <div class="col-6">
                        <label for="price">Giá bán (VNĐ) <span class="required">*</span></label>
                        <input type="number" id="price" name="price" required min="1000" placeholder="VD: 15000000">
                    </div>
                    <div class="col-6">
                        <label for="stock">Số lượng tồn kho <span class="required">*</span></label>
                        <input type="number" id="stock" name="stock" required min="0" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Hình ảnh Sản phẩm <span class="required">*</span></label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description" rows="6" placeholder="Mô tả các thông số kỹ thuật, tính năng nổi bật..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Lưu Sản phẩm</button>
                    <a href="products.php" class="btn btn-cancel">Hủy bỏ</a>
                </div>
            </form>
        </div>

    </div>

</body>
</html>