<?php
// public/user/sidebar_account.php

// Trang hiện tại (để set .active)
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="account-card account-sidebar">
  <h2>Tài khoản</h2>

  <nav class="account-nav">
    <!-- Thông tin cá nhân -->
    <a href="public/user/profile.php"
       class="<?= $currentPage === 'profile.php' ? 'active' : '' ?>">
      <span class="icon">
        <img
          src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
          alt="Thông tin cá nhân"
        >
      </span>
      <span>Thông tin cá nhân</span>
    </a>

    <!-- Chỉnh sửa thông tin -->
    <a href="public/user/edit_profile.php"
       class="<?= $currentPage === 'edit_profile.php' ? 'active' : '' ?>">
      <span class="icon">
        <img
          src="https://cdn-icons-png.flaticon.com/512/1827/1827933.png"
          alt="Chỉnh sửa thông tin"
        >
      </span>
      <span>Chỉnh sửa thông tin</span>
    </a>

    <!-- Đổi mật khẩu -->
    <a href="public/user/change_password.php"
       class="<?= $currentPage === 'change_password.php' ? 'active' : '' ?>">
      <span class="icon">
        <img
          src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png"
          alt="Đổi mật khẩu"
        >
      </span>
      <span>Đổi mật khẩu</span>
    </a>

    <!-- Quản lý đơn hàng -->
    <a href="public/user/orders.php"
       class="<?= $currentPage === 'orders.php' ? 'active' : '' ?>">
      <span class="icon">
        <img
          src="https://cdn-icons-png.flaticon.com/512/679/679922.png"
          alt="Quản lý đơn hàng"
        >
      </span>
      <span>Quản lý đơn hàng</span>
    </a>

    <!-- Đăng xuất -->
    <a href="public/user/logout.php" class="logout">
      <span class="icon">
        <img
          src="https://cdn-icons-png.flaticon.com/512/1828/1828479.png"
          alt="Đăng xuất"
        >
      </span>
      <span>Đăng xuất</span>
    </a>
  </nav>
</aside>
