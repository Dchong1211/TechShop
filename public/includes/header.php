<?php
// PHẢI BẮT ĐẦU SESSION ĐỂ KIỂM TRA ĐĂNG NHẬP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$PAGE_TITLE = $PAGE_TITLE ?? 'Techshop';
$SHOW_SEARCH = $SHOW_SEARCH ?? false;
$ADDITIONAL_HEAD_CONTENT = $ADDITIONAL_HEAD_CONTENT ?? '';
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light"> <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($PAGE_TITLE) ?> | Techshop</title>
  
  <base href="/TechShop/">
  
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=2">

  <style>
    /* CSS cho nút gạt theme */
    .theme-toggle {
      background: #f0f0f0;
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      margin-left: 10px; /* Thêm khoảng cách */
      /* Ghi đè style của .header-actions a */
      color: #333; 
    }
    .theme-toggle span {
      font-size: 16px;
      line-height: 1;
    }
    /* Ẩn icon không hoạt động */
    html[data-theme="light"] .theme-toggle .icon-dark { display: none; }
    html[data-theme="dark"] .theme-toggle .icon-light { display: none; }

    /* Thêm transition cho mượt */
    body, .main-header, .main-footer, .category-sidebar, 
    .product-card, .pdp-page {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
  </style>

  <script>
    (function() {
      // Lấy theme đã lưu từ lần trước
      const savedTheme = localStorage.getItem('theme') || 'light';
      // Áp dụng theme ngay lập tức
      document.documentElement.setAttribute('data-theme', savedTheme);
      
      // Nếu là dark mode, chúng ta cần load file CSS dark
      if (savedTheme === 'dark') {
        const darkThemeLink = document.createElement('link');
        darkThemeLink.rel = 'stylesheet';
        darkThemeLink.id = 'dark-theme-link';
        darkThemeLink.href = 'public/assets/css/cssUser/dark_theme.css?v=1';
        document.head.appendChild(darkThemeLink);
      }
    })();
  </script>
  <?= $ADDITIONAL_HEAD_CONTENT ?>
</head>
<body>
  <header class="main-header" role="banner">
    <div class="header-inner">
      <a href="public/user/dashboard.php" class="logo" aria-label="Về trang chủ Techshop">Techshop</a>
      
      <?php if ($SHOW_SEARCH): ?>
      <form class="search-box" role="search" aria-label="Tìm kiếm sản phẩm" action="public/user/product.php" method="get">
        <input type="hidden" name="cate" value="search">
        <input type="text" name="q" placeholder="Bạn cần tìm gì?" aria-label="Từ khóa tìm kiếm">
        <button type="submit">Tìm</button>
      </form>
      <?php endif; ?>

      <nav class="header-actions" aria-label="Liên kết nhanh">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="public/user/profile.php" style="font-weight: 600;">
                Chào, <?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES) ?>
            </a>
            <a href="public/user/orders.php">Đơn hàng</a>
            <a href="public/user/cart.php">Giỏ hàng</a>
            <a href="public/user/logout.php" style="color: #ff4d4f;">Đăng xuất</a>
            
        <?php else: ?>
            <a href="tel:19001234">Hotline</a>
            <a href="public/user/cart.php">Giỏ hàng</a>
            
            <a href="public/admin/login.php" style="font-weight: 600;">Đăng nhập</a>
            <a href="public/admin/register.php">Đăng ký</a>
        <?php endif; ?>

        <button class="theme-toggle" id="theme-toggle" title="Đổi giao diện">
            <span class="icon-light">☀️</span>
            <span class="icon-dark">🌙</span>
        </button>
      </nav>
      </div>
    
    <?php
    include 'navbar.php'; 
    ?>
  </header>