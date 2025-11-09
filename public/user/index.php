<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Techshop | Trang chủ</title>

  <!-- Base để mọi đường dẫn tương đối bám theo /TechShop/ -->
  <base href="/TechShop/">

  <!-- CSS -->
  <link rel="stylesheet" href="public/assets/css/user.css?v=6">
</head>
<body>
  <header class="main-header">
    <div class="header-inner">
      <a href="public/user/index.php" class="logo">Techshop</a>

      <div class="search-box">
        <input type="text" placeholder="Bạn cần tìm gì?">
        <button>🔍</button>
      </div>

      <div class="header-actions">
        <a href="#">📞 Hotline</a>
        <a href="public/user/orders.php">📦 Đơn hàng</a>
        <a href="public/user/cart.php">🛒 Giỏ hàng</a>
        <a href="public/user/login.php">👤 Đăng nhập</a>
      </div>
    </div>

    <nav class="main-nav">
      <div class="nav-inner">
        <a href="public/user/products.php?cate=pc">Mua PC</a>
        <a href="public/user/products.php?cate=hot">Hot Deal</a>
        <a href="public/user/products.php?cate=laptop">Laptop</a>
        <a href="public/user/products.php?cate=monitor">Màn hình</a>
        <a href="public/user/products.php?cate=gear">Bàn phím - Chuột</a>
        <a href="public/user/products.php?cate=accessories">Phụ kiện</a>
      </div>
    </nav>
  </header>

  <main class="homepage">
    <div class="homepage-grid">
      <aside class="category-sidebar">
        <h3>Danh mục</h3>
        <ul>
          <li><a href="public/user/products.php?cate=pc">PC Gaming</a></li>
          <li><a href="public/user/products.php?cate=laptop">Laptop</a></li>
          <li><a href="public/user/products.php?cate=monitor">Màn hình</a></li>
          <li><a href="public/user/products.php?cate=gear">Gaming Gear</a></li>
          <li><a href="public/user/products.php?cate=audio">Âm thanh</a></li>
          <li><a href="public/user/products.php?cate=accessories">Phụ kiện</a></li>
        </ul>
      </aside>

      <section class="banner-area">
        <div class="main-banner">
          <img src="https://via.placeholder.com/900x260/4aa3ff/ffffff?text=Techshop+Banner" alt="">
        </div>
        <div class="sub-banners">
          <img src="https://via.placeholder.com/280x120/ffb347/ffffff?text=PC+Gaming" alt="">
          <img src="https://via.placeholder.com/280x120/ff6f69/ffffff?text=Keyboard+Sale" alt="">
          <img src="https://via.placeholder.com/280x120/96ceb4/ffffff?text=Monitor+Deal" alt="">
        </div>
      </section>

      <aside class="right-banners">
        <img src="https://via.placeholder.com/180x260/e74c3c/ffffff?text=Deal+hot" alt="">
        <img src="https://via.placeholder.com/180x160/2ecc71/ffffff?text=Giảm+giá" alt="">
      </aside>
    </div>

    <section class="product-section">
      <div class="section-head">
        <h2>PC bán chạy</h2>
        <a href="public/user/products.php?cate=pc">Xem tất cả</a>
      </div>
      <div class="products" id="pc-hot">
        <p>Không có sản phẩm.</p>
      </div>
    </section>

    <section class="product-section">
      <div class="section-head">
        <h2>Laptop bán chạy</h2>
        <a href="public/user/products.php?cate=laptop">Xem tất cả</a>
      </div>
      <div class="products" id="laptop-hot">
        <p>Không có sản phẩm.</p>
      </div>
    </section>

    <section class="product-section">
      <div class="section-head">
        <h2>Gear gaming</h2>
        <a href="public/user/products.php?cate=gear">Xem tất cả</a>
      </div>
      <div class="products" id="gear-hot">
        <p>Không có sản phẩm.</p>
      </div>
    </section>
  </main>

  <footer>
    © <?= date('Y') ?> Techshop
  </footer>

  <!-- JS -->
  <script src="public/assets/js/user.js?v=6"></script>
</body>
</html>
