<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản phẩm Mới</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

    <div class="app-wrapper">
        
        <?php 
        $active_page = 'products'; 
        
        include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
        ?>

        <main class="main-content">
            
            <div class="card">
                <form action="products.php" method="POST" enctype="multipart/form-data" class="product-form">
                    
                    <div class="card-header">
                        <h5 class="card-title">Thêm Sản phẩm Mới</h5>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên Sản phẩm <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required placeholder="Nhập tên sản phẩm" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Danh mục <span class="required">*</span></label>
                            <select id="category" name="category" required class="form-control">
                                <option value="">Chọn danh mục</option>
                                <option value="1">Laptop</option>
                                <option value="2">Điện thoại</option>
                                <option value="3">Phụ kiện</option>
                            </select>
                        </div>
                        
                        <div class="form-group row-group">
                            <div class="col-6">
                                <label for="price">Giá bán (VNĐ) <span class="required">*</span></label>
                                <input type="number" id="price" name="price" required min="1000" placeholder="VD: 15000000" class="form-control">
                            </div>
                            <div class="col-6">
                                <label for="stock">Số lượng tồn kho <span class="required">*</span></label>
                                <input type="number" id="stock" name="stock" required min="0" value="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">Hình ảnh Sản phẩm <span class="required">*</span></label>
                            <input type="file" id="image" name="image" accept="image/*" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả chi tiết</label>
                            <textarea id="description" name="description" rows="6" placeholder="Mô tả các thông số kỹ thuật, tính năng nổi bật..." class="form-control"></textarea>
                        </div>
                    </div> <div class="card-footer">
                        <a href="products.php" class="btn btn-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Lưu Sản phẩm</button>
                    </div>
                    
                </form>
            </div> 
        </main> 
        
    </div> 
    <script src="../assets/js/admin.js"></script>

</body>
</html>