<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <?php require_once __DIR__ . '/../../app/helpers/CSRF.php'; ?>
    <meta name="csrf-token" content="<?= CSRF::token(); ?>">
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
                    <h5 class="card-title">Quản lý Sản phẩm</h5>
                    
                    <div class="table-actions">
                        <div class="search-box">
                            <input type="text" placeholder="Tìm kiếm sản phẩm...">
                            <button class="btn btn-search">Tìm</button>
                        </div>
                        <a href="add_products.php?action=add" class="btn btn-primary">Thêm Sản phẩm Mới</a>
                    </div>
                </div>
                
                <div class="card-body">
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
                                    <td><span class="status status-inactive">Hết hàng</span></td>
                                    <td class="action-buttons">
                                        <a href="edit_products.php?action=edit" class="btn btn-edit">Sửa</a>
                                        <form method="POST" action="products.php" style="display:inline;">
                                            <input type="hidden" name="product_id" value="102">
                                            <button type="submit" name="action" value="delete" class="btn btn-delete">Xóa</button>
                                        </form>
                                        <a href="detail_products.php" class="btn btn-detail">Xem</a>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>103</td>
                                    <td>Laptop Gaming Acer Nitro 5</td>
                                    <td>Laptop</td>
                                    <td>25.000.000 VNĐ</td>
                                    <td>50</td>
                                    <td><span class="status status-active">Còn hàng</span></td>
                                    <td class="action-buttons">
                                        <a href="edit_products.php?action=edit" class="btn btn-edit">Sửa</a>
                                        <form method="POST" action="products.php" style="display:inline;">
                                            <input type="hidden" name="product_id" value="103">
                                            <button type="submit" name="action" value="delete" class="btn btn-delete">Xóa</button>
                                        </form>
                                        <a href="detail_products.php" class="btn btn-detail">Xem</a>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div> </div> </main> 
        
    <script src="/TechShop/public/assets/js/admin.js"></script>

</body>
</html>