<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Techshop | Trang chủ</title>
  <base href="/TechShop/">
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=7">
  <meta name="description" content="Techshop - PC, Laptop, Màn hình, Gaming Gear và phụ kiện công nghệ."/>
</head>
<body>
  <header class="main-header" role="banner">
    <div class="header-inner">
      <a href="public/user/index.php" class="logo" aria-label="Về trang chủ Techshop">Techshop</a>
      <form class="search-box" role="search" aria-label="Tìm kiếm sản phẩm" action="public/user/product.php" method="get">
        <input type="hidden" name="cate" value="search">
        <input type="text" name="q" placeholder="Bạn cần tìm gì?" aria-label="Từ khóa tìm kiếm">
        <button type="submit" aria-label="Tìm kiếm">🔍</button>
      </form>
      <nav class="header-actions" aria-label="Liên kết nhanh">
        <a href="tel:19001234">📞 Hotline</a>
        <a href="public/user/order.php">📦 Đơn hàng</a>
        <a href="public/user/cart.php">🛒 Giỏ hàng</a>
      </nav>
    </div>
    <nav class="main-nav" aria-label="Danh mục chính">
      <div class="nav-inner">
        <a href="public/user/product.php?cate=pc">Mua PC</a>
        <a href="public/user/product.php?cate=hot">Hot Deal</a>
        <a href="public/user/product.php?cate=laptop">Laptop</a>
        <a href="public/user/product.php?cate=monitor">Màn hình</a>
        <a href="public/user/product.php?cate=gear">Bàn phím - Chuột</a>
        <a href="public/user/product.php?cate=accessories">Phụ kiện</a>
      </div>
    </nav>
  </header>

  <main class="homepage" role="main">
    <div class="homepage-grid">
      <aside class="category-sidebar" aria-label="Danh mục bên">
        <h3>Danh mục</h3>
        <ul>
          <li><a href="public/user/product.php?cate=pc">PC Gaming</a></li>
          <li><a href="public/user/product.php?cate=laptop">Laptop</a></li>
          <li><a href="public/user/product.php?cate=monitor">Màn hình</a></li>
          <li><a href="public/user/product.php?cate=gear">Gaming Gear</a></li>
          <li><a href="public/user/product.php?cate=audio">Âm thanh</a></li>
          <li><a href="public/user/product.php?cate=accessories">Phụ kiện</a></li>
        </ul>
      </aside>

      <section class="banner-area" aria-label="Khu vực banner">
        <div class="main-banner">
          <a href="public/user/product.php?cate=hot" aria-label="Xem Hot Deal">
            <img loading="lazy" src="https://via.placeholder.com/900x260/4aa3ff/ffffff?text=Techshop+Banner" alt="Techshop Banner">
          </a>
        </div>
        <div class="sub-banners">
          <a href="public/user/product.php?cate=pc" aria-label="Xem PC Gaming">
            <img loading="lazy" src="https://via.placeholder.com/280x120/ffb347/ffffff?text=PC+Gaming" alt="Khuyến mãi PC Gaming">
          </a>
          <a href="public/user/product.php?cate=gear" aria-label="Xem Gear">
            <img loading="lazy" src="https://via.placeholder.com/280x120/ff6f69/ffffff?text=Keyboard+Sale" alt="Giảm giá bàn phím">
          </a>
          <a href="public/user/product.php?cate=monitor" aria-label="Xem Màn hình">
            <img loading="lazy" src="https://via.placeholder.com/280x120/96ceb4/ffffff?text=Monitor+Deal" alt="Khuyến mãi màn hình">
          </a>
        </div>
      </section>

      <aside class="right-banners" aria-label="Khuyến mãi bên phải">
        <a href="public/user/product.php?cate=hot" aria-label="Deal hot">
          <img loading="lazy" src="https://via.placeholder.com/180x260/e74c3c/ffffff?text=Deal+hot" alt="Deal hot">
        </a>
        <a href="public/user/product.php?cate=accessories" aria-label="Giảm giá phụ kiện">
          <img loading="lazy" src="https://via.placeholder.com/180x160/2ecc71/ffffff?text=Giảm+giá" alt="Giảm giá phụ kiện">
        </a>
      </aside>
    </div>

    <section class="product-section" aria-labelledby="pc-title">
      <div class="section-head">
        <h2 id="pc-title"><a href="public/user/product.php?cate=pc">PC bán chạy</a></h2>
        <a class="view-all" href="public/user/product.php?cate=pc">Xem tất cả</a>
      </div>
      <div class="products" id="pc-hot" data-endpoint="api/products.php?cate=pc">
        <p class="empty">Không có sản phẩm.</p>
      </div>
    </section>

    <section class="product-section" aria-labelledby="laptop-title">
      <div class="section-head">
        <h2 id="laptop-title"><a href="public/user/product.php?cate=laptop">Laptop bán chạy</a></h2>
        <a class="view-all" href="public/user/product.php?cate=laptop">Xem tất cả</a>
      </div>
      <div class="products" id="laptop-hot" data-endpoint="api/products.php?cate=laptop">
        <p class="empty">Không có sản phẩm.</p>
      </div>
    </section>

    <section class="product-section" aria-labelledby="gear-title">
      <div class="section-head">
        <h2 id="gear-title"><a href="public/user/product.php?cate=gear">Gear gaming</a></h2>
        <a class="view-all" href="public/user/product.php?cate=gear">Xem tất cả</a>
      </div>
      <div class="products" id="gear-hot" data-endpoint="api/products.php?cate=gear">
        <p class="empty">Không có sản phẩm.</p>
      </div>
    </section>
  </main>

  <footer role="contentinfo">
    © <?= date('Y') ?> Techshop
  </footer>

  <script src="public/assets/js/user.js?v=6"></script>

  <style>
    :root { --accent: #4dd0e1; }
    .main-nav .nav-inner a{ transition: color .2s ease; }
    .main-nav .nav-inner a:hover{ color: var(--accent); }
  </style>
</body>
</html>
