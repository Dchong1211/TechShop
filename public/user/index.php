<?php
define('BASE_PATH', dirname(__DIR__));

$PAGE_TITLE = 'Trang chủ';
$SHOW_SEARCH = true;

ob_start();
?>
<script src="public/assets/js/user.js?v=1"></script>
<style>
  :root { --accent: #4dd0e1; }
  .main-nav .nav-inner a{ transition: color .2s ease; }
  .main-nav .nav-inner a:hover{ color: var(--accent); }
</style>
<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/header.php';
?>

<div class="sticky-banner-container-left">
  <a href="#">
    <img loading="lazy" src="public/assets/images/banner_doc_1.jpg" alt="Banner dọc 1">
  </a>
</div>
<div class="sticky-banner-container-right">
  <a href="#">
    <img loading="lazy" src="public/assets/images/banner_doc_2.jpg" alt="Banner dọc 2">
  </a>
</div>
<main class="homepage" role="main">
    
    <div class="main-content">
      <div class="row">

        <div class="col-xl-3 col-lg-3 col-left-sidebar">
          
          <?php include BASE_PATH . '/includes/sidebar.php'; ?>

          <div class="sidebar-megamenu-container">
            <?php 
              $megamenu_panel_file = BASE_PATH . '/includes/megamenu_panels.php';
              if (file_exists($megamenu_panel_file)) {
                  include $megamenu_panel_file;
              }
            ?>
          </div>

        </div>
        <div class="col-xl-9 col-lg-9 col-main-content">
          
          <div class="top-banners">
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/main_banner1.jpg" alt="Top Banner 1">
              </a>
            </div>
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/main_banner2.jpg" alt="Top Banner 2">
              </a>
            </div>
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/main_banner3.jpg" alt="Top Banner 3">
              </a>
            </div>
          </div>

          <div class="main-slider-banner">
            <a href="#">
              <img loading="lazy" src="public/assets/images/main_banner1.jpg" alt="Main Slider Banner">
            </a>
          </div>

          <div class="deal-banners">
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/deal1.jpg" alt="Deal 1">
              </a>
            </div>
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/deal2.jpg" alt="Deal 2">
              </a>
            </div>
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/deal3.jpg" alt="Deal 3">
              </a>
            </div>
            <div class="banner-item">
              <a href="#">
                <img loading="lazy" src="public/assets/images/deal4.jpg" alt="Deal 4">
              </a>
            </div>
          </div>

          <section class="product-section" aria-labelledby="pc-title">
            <div class="section-head">
              <h2 id="pc-title"><a href="public/user/product.php?cate=pc">PC bán chạy</a></h2>
              <a class="view-all" href="public/user/product.php?cate=pc">Xem tất cả</a>
            </div>
            <div class="products" id="pc-hot">
              <p class="empty">Không có sản phẩm.</p>
            </div>
          </section>

          <section class="product-section" aria-labelledby="laptop-title">
            <div class="section-head">
              <h2 id="laptop-title"><a href="public/user/product.php?cate=laptop">Laptop bán chạy</a></h2>
              <a class="view-all" href="public/user/product.php?cate=laptop">Xem tất cả</a>
            </div>
            <div class="products" id="laptop-hot">
              <p class="empty">Không có sản phẩm.</p>
            </div>
          </section>

          <section class="product-section" aria-labelledby="gear-title">
            <div class="section-head">
              <h2 id="gear-title"><a href="public/user/product.php?cate=gear">Gear gaming</a></h2>
              <a class="view-all" href="public/user/product.php?cate=gear">Xem tất cả</a>
            </div>
            <div class="products" id="gear-hot">
              <p class="empty">Không có sản phẩm.</p>
            </div>
          </section>
          
          <section class="product-section brand-section">
            <div class="section-head">
              <h2 id="brand-title">THƯƠNG HIỆU NỔI BẬT</h2>
            </div>
            <div class="brand-grid">
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=ASUS" alt="ASUS"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=GIGABYTE" alt="GIGABYTE"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=MSI" alt="MSI"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=ACER" alt="ACER"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=RAZER" alt="RAZER"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=CORSAIR" alt="CORSAIR"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=LOGITECH" alt="LOGITECH"></a></div>
              <div class="brand-item"><a href="#"><img loading="lazy" src="https://via.placeholder.com/120x60/f0f0f0/000?text=LENOVO" alt="LENOVO"></a></div>
            </div>
          </section>
        </div>

        </div>
    </div>
  </main>

<?php
include BASE_PATH . '/includes/footer.php';
?>