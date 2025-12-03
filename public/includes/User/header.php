<?php
// KHỞI ĐỘNG SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cấu hình mặc định
$PAGE_TITLE = $PAGE_TITLE ?? 'Techshop';
$SHOW_SEARCH = $SHOW_SEARCH ?? false;
$ADDITIONAL_HEAD_CONTENT = $ADDITIONAL_HEAD_CONTENT ?? '';
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light"> 
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($PAGE_TITLE) ?> | Techshop</title>
  
  <base href="/TechShop/">
  
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=10005">
  <link rel="stylesheet" href="public/assets/css/cssUser/components.css?v=10005">
  <link rel="stylesheet" href="public/assets/css/cssUser/megamenu.css?v=10005">
  <link rel="stylesheet" href="public/assets/css/cssUser/profile.css?v=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    /* CSS CHO NÚT DARK MODE */
    .theme-toggle {
      background: #f0f0f0;
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 5px 10px; 
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: 10px; 
      transition: all 0.3s ease;
    }
    
    .theme-toggle img {
      width: 20px; height: 20px;
      object-fit: contain;
      transition: transform 0.3s ease;
    }
    
    .theme-toggle:hover img {
        transform: rotate(15deg); 
    }

    /* Logic ẩn hiện Icon */
    html[data-theme="light"] .theme-toggle .icon-dark { display: none; }
    html[data-theme="dark"] .theme-toggle .icon-light { display: none; }

    /* Logic màu sắc Icon */
    html[data-theme="dark"] .theme-toggle img {
        filter: invert(1); /* Đảo màu trắng khi ở Dark Mode */
    }
    
    html[data-theme="dark"] .theme-toggle {
        background: #333;
        border-color: #555;
    }

    /* Transition mượt mà toàn trang */
    body, .main-header, .main-footer, .category-sidebar, 
    .product-card, .pdp-page, .cart-left-section, .summary-box {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
  </style>

  <script>
    (function() {
      // Kiểm tra và áp dụng theme đã lưu từ trước
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', savedTheme);
      
      // Nếu là dark mode -> load thêm file CSS
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
      
      <a href="public/" class="logo" aria-label="Về trang chủ Techshop">Techshop</a>
      
      <?php if ($SHOW_SEARCH): ?>
      <form class="search-box" role="search" action="public/user/product.php" method="get">
        <input type="hidden" name="cate" value="search">
        <input type="text" name="q" placeholder="Bạn cần tìm gì?" aria-label="Từ khóa tìm kiếm">
        <button type="submit">Tìm</button>
      </form>
      <?php endif; ?>

      <nav class="header-actions" aria-label="Liên kết nhanh">
        
        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-dropdown">
                <a href="public/user/profile.php" class="user-btn">
                    <i class="fa-regular fa-user"></i> 
                    Chào, <?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES) ?>
                    <i class="fa-solid fa-caret-down" style="font-size: 10px; margin-left: 5px;"></i>
                </a>
                
                <div class="dropdown-menu">
                    <a href="public/user/profile.php">
                        <i class="fa-solid fa-id-card"></i> Thông tin tài khoản
                    </a>
                    <a href="public/user/orders.php">
                        <i class="fa-solid fa-box-open"></i> Đơn hàng của tôi
                    </a>
                     <a href="public/user/cart.php">
                        <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                    </a>
                    <a href="public/user/logout.php" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </div>
            </div>

        <?php else: ?>
            <a href="tel:19001234">Hotline</a>
            <a href="public/user/cart.php">Giỏ hàng</a>
            
            <a href="public/admin/login.php" style="font-weight: 600;">Đăng nhập</a>
            <a href="public/admin/register.php">Đăng ký</a>
        <?php endif; ?>

        <button class="theme-toggle" id="theme-toggle" title="Đổi giao diện">
            <img src="https://img.icons8.com/ios-glyphs/30/000000/sun--v1.png" alt="Light Mode" class="icon-light">
            <img src="https://img.icons8.com/ios-glyphs/30/000000/moon-symbol.png" alt="Dark Mode" class="icon-dark">
        </button>

      </nav>
    </div>
    
    <?php include 'navbar.php'; ?>
    
  </header>