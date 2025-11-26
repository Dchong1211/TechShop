<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Sản phẩm</title>
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
                <div class="card-header">
                    <h5 class="card-title">Chi tiết: Sản phẩm </h5>
                </div>
                
                <div class="card-body">
                    <div class="detail-box">
                        
                        <div class="detail-image">
                            <img src="https://placehold.co/400x300/a8d8e0/34495e?text=Samsung+S22" alt="Hình ảnh sản phẩm">
                        </div>

                        <div class="detail-info">
                            <p class="status-badge-wrapper">
                                Trạng thái: 
                                <span class="status status-inactive">Hết hàng</span>
                            </p>

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
                </div> <div class="card-footer">
                    <a href="edit_products.php?id=102" class="btn btn-edit">Chỉnh sửa</a>
                    <a href="products.php" class="btn btn-secondary">Quay lại danh sách</a>
                </div>

            </div> 
        </main> 
    </div>
    <script src="../assets/js/admin.js"></script>

</body>
</html>