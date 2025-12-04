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
    <title>Quản lý Người dùng</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="<?= $csrf ?>">

    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

<div class="app-wrapper">

    <?php 
        $active_page = 'users'; 
        include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
    ?>

    <main class="main-content">

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Quản lý Người dùng</h5>

                <div class="table-actions">

                    <!-- Nếu bro muốn thêm user -->
                    <a href="/TechShop/admin/users/add" class="btn btn-primary">
                        Thêm Người dùng
                    </a>

                    <div class="search-box">
                        <input id="searchInput" type="text" placeholder="Tìm kiếm người dùng...">
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
                                <th>Avatar</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>

                        <tbody id="userTableBody"></tbody>
                    </table>

                </div>

                <div id="paginationBox" class="pagination" style="margin-top: 20px; text-align: end;"></div>

            </div>

        </div>

    </main>

</div>

<!-- JS -->
<script type="module" src="/TechShop/public/assets/js/user.js"></script>
<script src="/TechShop/public/assets/js/admin.js"></script>

</body>
</html>
