<?php
    require_once __DIR__ . '/../../app/helpers/CSRF.php';
    $csrf = CSRF::token();
    requireAdmin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <meta name="csrf-token" content="<?= $csrf ?>">
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css"> 
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
                <h5 class="card-title">Quản lý Sản phẩm</h5>

                <div class="table-actions">
                    <a href="/TechShop/admin/products/add" class="btn btn-primary">
                        Thêm Sản phẩm Mới
                    </a>

                    <div class="search-box">
                        <input id="searchInput" type="text" placeholder="Tìm kiếm sản phẩm...">
                        <button class="btn btn-search">Tìm</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="product-table-container">

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình</th>
                                <th>Tên Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>

                        <tbody id="productTableBody"></tbody>
                    </table>

                </div>
                <div id="paginationBox" class="pagination" style="margin-top: 20px; text-align: end;"></div>

            </div>

        </div>

    </main>
</div>

<script type="module" src="/TechShop/public/assets/js/admin_products.js"></script>
<script src="/TechShop/public/assets/js/admin.js"></script>

</body>
</html>
