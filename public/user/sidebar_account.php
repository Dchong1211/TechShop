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
        <i class="fa-regular fa-user"></i>
      </span>
      <span>Thông tin cá nhân</span>
    </a>

    <!-- Chỉnh sửa thông tin -->
    <a href="public/user/edit_profile.php"
       class="<?= $currentPage === 'edit_profile.php' ? 'active' : '' ?>">
      <span class="icon">
        <i class="fa-solid fa-user-pen"></i>
      </span>
      <span>Chỉnh sửa thông tin</span>
    </a>

    <!-- Đổi mật khẩu -->
    <a href="public/user/change_password.php"
       class="<?= $currentPage === 'change_password.php' ? 'active' : '' ?>">
      <span class="icon">
        <i class="fa-solid fa-key"></i>
      </span>
      <span>Đổi mật khẩu</span>
    </a>

    <!-- Quản lý đơn hàng -->
    <a href="public/user/orders.php"
       class="<?= $currentPage === 'orders.php' ? 'active' : '' ?>">
      <span class="icon">
        <i class="fa-solid fa-box-open"></i>
      </span>
      <span>Quản lý đơn hàng</span>
    </a>

    <!-- Đăng xuất -->
    <a href="public/user/logout.php" class="logout">
      <span class="icon">
        <i class="fa-solid fa-right-from-bracket"></i>
      </span>
      <span>Đăng xuất</span>
    </a>
  </nav>
</aside>
