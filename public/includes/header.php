<?php
$PAGE_TITLE = $PAGE_TITLE ?? 'Techshop';
$SHOW_SEARCH = $SHOW_SEARCH ?? false;
$ADDITIONAL_HEAD_CONTENT = $ADDITIONAL_HEAD_CONTENT ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($PAGE_TITLE) ?> | Techshop</title>
  <base href="/TechShop/">
  
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=2">

  <?= $ADDITIONAL_HEAD_CONTENT ?>
</head>
<body>
  <header class="main-header" role="banner">
    <div class="header-inner">
      <a href="public/user/index.php" class="logo" aria-label="Về trang chủ Techshop">Techshop</a>
      
      <?php if ($SHOW_SEARCH): ?>
      <form class="search-box" role="search" aria-label="Tìm kiếm sản phẩm" action="public/user/product.php" method="get">
        <input type="hidden" name="cate" value="search">
        <input type="text" name="q" placeholder="Bạn cần tìm gì?" aria-label="Từ khóa tìm kiếm">
        <button type="submit">Tìm</button>
      </form>
      <?php endif; ?>

      <nav class="header-actions" aria-label="Liên kết nhanh">
        <a href="tel:19001234">Hotline</a>
        <a href="public/user/orders.php">Đơn hàng</a>
        <a href="public/user/cart.php">Giỏ hàng</a>
      </nav>
    </div>
    
    <?php
    include 'navbar.php'; 
    ?>

  </header>