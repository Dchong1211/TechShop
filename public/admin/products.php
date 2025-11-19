<?php
session_start();
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf_token = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $csrf_token ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm | Admin Panel</title>
    <base href="/TechShop/">
    <link rel="stylesheet" href="public/assets/css/cssAdmin/main_admin.css">
    <link rel="stylesheet" href="public/assets/css/cssAdmin/products.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .loading-row {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="app-wrapper">

        <nav class="top-navbar">
            <div class="navbar-left">
                <a href="public/admin/index.php" class="navbar-brand">TechShop</a>
                <button class="sidebar-toggle" type="button">☰</button>
            </div>
            <div class="navbar-search">
                <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm...">
            </div>
            <div class="navbar-right">
                <button class="theme-toggle" id="theme-toggle">
                    <span class="icon-sun">☀️</span><span class="icon-moon">🌙</span>
                </button>
                <a href="#" class="nav-icon user-avatar">Admin</a>
            </div>
        </nav>

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="public/admin/index.php"><span class="icon"><i class="bi bi-house"></i></span><span class="title">Dashboard</span></a></li>
                    <li><a href="public/admin/products.php" class="active"><span class="icon"><i class="bi bi-box"></i></span><span class="title">Quản lý Sản phẩm</span></a></li>
                    <li><a href="public/admin/orders.php"><span class="icon"><i class="bi bi-cart"></i></span><span class="title">Quản lý Đơn hàng</span></a></li>
                    <li><a href="public/admin/users.php"><span class="icon"><i class="bi bi-people"></i></span><span class="title">Quản lý Người dùng</span></a></li>
                    <li class="sidebar-logout"><a href="#"><span class="icon"><i class="bi bi-box-arrow-right"></i></span><span class="title">Đăng xuất</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quản lý Sản phẩm</h5>
                    <div class="table-actions">
                        <a href="public/admin/add_products.php" class="btn btn-primary">Thêm Sản phẩm Mới</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="product-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên Sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody id="product-list">
                                <tr>
                                    <td colspan="7" class="loading-row">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <script src="public/assets/js/admin.js"></script>
    <script src="public/assets/js/admin_products.js"></script>

</body>

</html>