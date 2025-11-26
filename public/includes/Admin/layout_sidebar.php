<?php
// Xác định tab đang active nếu chưa được set
if (!isset($active_page)) {
    $active_page = '';
}
?>

<nav class="top-navbar">
    <div class="navbar-left">
        <a href="/TechShop/admin/dashboard" class="navbar-brand">TechShop</a>
        <button class="sidebar-toggle" type="button">☰</button>
    </div>
    <div class="navbar-search">
        <input type="text" placeholder="Search...">
    </div>
    <div class="navbar-right">
        <button class="theme-toggle" id="theme-toggle" type="button" title="Chuyển đổi Sáng/Tối">
            <span class="icon-sun"><i class="bi bi-sun" style="color: #5e6e82"></i></span>
            <span class="icon-moon"><i class="bi bi-moon" style="color: #5e6e82"></i></span>
        </button>
        <a href="#" class="nav-icon"><i class="bi bi-bell" style="color: #5e6e82"></i></a>
        <a href="#" class="nav-icon"><i class="bi bi-gear" style="color: #5e6e82"></i></a>
        <a href="#" class="nav-icon user-avatar">
            <?= isset($_SESSION['user']['name']) ? substr($_SESSION['user']['name'], 0, 1) : 'A' ?>
        </a>
    </div>
</nav>

<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="/TechShop/admin/dashboard" class="<?= $active_page == 'dashboard' ? 'active' : '' ?>">
                    <span class="icon"><i class="bi bi-house" style="color: #5e6e82"></i></span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/TechShop/admin/products" class="<?= in_array($active_page, ['products', 'add_products', 'edit_products', 'detail_products']) ? 'active' : '' ?>">
                    <span class="icon"><i class="bi bi-box" style="color: #5e6e82"></i></span>
                    <span class="title">Quản lý Sản phẩm</span>
                </a>
            </li>
            <li>
                <a href="/TechShop/admin/orders" class="<?= in_array($active_page, ['orders', 'order_detail']) ? 'active' : '' ?>">
                    <span class="icon"><i class="bi bi-cart" style="color: #5e6e82"></i></span>
                    <span class="title">Quản lý Đơn hàng</span>
                </a>
            </li>
            <li>
                <a href="/TechShop/admin/users" class="<?= in_array($active_page, ['users', 'edit_user']) ? 'active' : '' ?>">
                    <span class="icon"><i class="bi bi-people" style="color: #5e6e82"></i></span>
                    <span class="title">Quản lý Người dùng</span>
                </a>
            </li>
            <li class="sidebar-logout">
                <a href="/TechShop/login.php">
                    <span class="icon"><i class="bi bi-box-arrow-right" style="color: #5e6e82"></i></span>
                    <span class="title">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>