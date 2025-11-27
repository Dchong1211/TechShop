<?php
// 1. CẤU HÌNH
define('BASE_PATH', dirname(__DIR__)); 
$PAGE_TITLE = 'Trang chủ';
$SHOW_SEARCH = true;

// (Giữ nguyên phần tạo dữ liệu giả...)
function create_product($id, $name, $price, $img, $label = '') {
    return ['id' => $id, 'name' => $name, 'price' => $price, 'image' => $img, 'label' => $label];
}
function multiply_data($data, $count = 12) {
    $result = [];
    while (count($result) < $count) {
        foreach ($data as $item) {
            if (count($result) >= $count) break;
            $new = $item;
            $new['id'] = $item['id'] . '_' . count($result); 
            $result[] = $new;
        }
    }
    return $result;
}

$base_laptop = [
    create_product(101, 'Laptop ASUS Vivobook Go 15', 11490000, 'asus_vivo.jpg', '-10%'),
    create_product(102, 'Laptop HP Pavilion 15', 14590000, 'hp_pavilion.jpg'),
    create_product(103, 'MacBook Air M1 2020', 18490000, 'macbook_m1.jpg', 'Hot'),
    create_product(104, 'Laptop Acer Nitro 5 Tiger', 19990000, 'acer_nitro.jpg'),
];
$base_pc = [
    create_product(201, 'PC GVN Intel i3 / GTX 1650', 9590000, 'pc_gvn_1.jpg'),
    create_product(202, 'PC GVN i5 12400F / RTX 3060', 15990000, 'pc_gvn_2.jpg', 'Best Seller'),
    create_product(203, 'PC GVN AMD Ryzen 5 / RX 6600', 14490000, 'pc_gvn_3.jpg'),
    create_product(204, 'PC GVN Dragon High-End', 85990000, 'pc_gvn_high.jpg', 'New'),
];
$base_gear = [
    create_product(301, 'Chuột Logitech G Pro X', 2990000, 'logitech_gpro.jpg'),
    create_product(302, 'Phím cơ Keychron K8 Pro', 2590000, 'keychron_k8.jpg'),
    create_product(303, 'Tai nghe HyperX Cloud II', 1890000, 'hyperx_cloud.jpg'),
    create_product(304, 'Ghế Gaming Anda Seat', 5900000, 'chair_anda.jpg', '-50%'),
];

$list_laptop = multiply_data($base_laptop, 12); 
$list_pc     = multiply_data($base_pc, 12);     
$list_gear   = multiply_data($base_gear, 12);

// DATA DANH MỤC NHANH
$quick_categories = [
    ['name' => 'Laptop', 'code' => 'laptop', 'icon' => 'fa-solid fa-laptop'],
    ['name' => 'PC', 'code' => 'pc', 'icon' => 'fa-solid fa-desktop'],
    ['name' => 'Màn hình', 'code' => 'monitor', 'icon' => 'fa-solid fa-tv'],
    ['name' => 'Mainboard', 'code' => 'mainboard', 'icon' => 'fa-solid fa-microchip'],
    ['name' => 'CPU', 'code' => 'cpu', 'icon' => 'fa-solid fa-memory'],
    ['name' => 'VGA', 'code' => 'vga', 'icon' => 'fa-solid fa-ticket'],
    ['name' => 'RAM', 'code' => 'ram', 'icon' => 'fa-solid fa-layer-group'],
    ['name' => 'Ổ cứng', 'code' => 'hdd', 'icon' => 'fa-solid fa-hard-drive'],
    ['name' => 'Case', 'code' => 'case', 'icon' => 'fa-solid fa-server'],
    ['name' => 'Tản nhiệt', 'code' => 'cooling', 'icon' => 'fa-solid fa-snowflake'],
    ['name' => 'Nguồn', 'code' => 'psu', 'icon' => 'fa-solid fa-plug'],
    ['name' => 'Bàn phím', 'code' => 'keyboard', 'icon' => 'fa-regular fa-keyboard'],
    ['name' => 'Chuột', 'code' => 'mouse', 'icon' => 'fa-solid fa-computer-mouse'],
    ['name' => 'Ghế', 'code' => 'chair', 'icon' => 'fa-solid fa-chair'],
    ['name' => 'Tai nghe', 'code' => 'headset', 'icon' => 'fa-solid fa-headset'],
    ['name' => 'Loa', 'code' => 'speaker', 'icon' => 'fa-solid fa-volume-high'],
    ['name' => 'Console', 'code' => 'console', 'icon' => 'fa-solid fa-gamepad'],
    ['name' => 'Phụ kiện', 'code' => 'accessories', 'icon' => 'fa-brands fa-usb'],
    ['name' => 'Thiết bị VP', 'code' => 'office', 'icon' => 'fa-solid fa-print'],
    ['name' => 'Sạc DP', 'code' => 'powerbank', 'icon' => 'fa-solid fa-battery-full'],
];

// DATA TIN TỨC
$news_list = [
    ['title' => 'Cách reset máy tính Casio chi tiết và đầy đủ cho mọi dòng', 'img' => 'https://via.placeholder.com/300x200?text=News+1'],
    ['title' => '3 cách tải Where Winds Meet trên PC, laptop chi tiết', 'img' => 'https://via.placeholder.com/300x200?text=News+2'],
    ['title' => 'Chi tiết từng cách giải phóng dung lượng Zalo bị đầy', 'img' => 'https://via.placeholder.com/300x200?text=News+3'],
    ['title' => 'Hướng dẫn cách tải Excel về máy tính, điện thoại', 'img' => 'https://via.placeholder.com/300x200?text=News+4'],
];

ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/index.css?v=10000">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="public/assets/js/user.js?v=9999"></script>
<script src="public/assets/js/indexUser.js?v=9999" defer></script> 

<style>
    /* CSS Cục bộ để xử lý layout 2 phần */
    .hero-section {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }
    
    /* Phần nội dung full-width bên dưới */
    .full-width-content {
        display: block;
        width: 100%;
    }
    
    /* Responsive cho mobile */
    @media (max-width: 991px) {
        .hero-section {
            grid-template-columns: 1fr;
        }
        .col-left-sidebar {
            display: none;
        }
    }
</style>

<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean(); 
include BASE_PATH . '/includes/User/header.php';
?>

<div class="sticky-banner-container-left"><a href="#"><img loading="lazy" src="public/assets/images/banner_doc_1.jpg" alt="Banner Trái"></a></div>
<div class="sticky-banner-container-right"><a href="#"><img loading="lazy" src="public/assets/images/banner_doc_2.jpg" alt="Banner Phải"></a></div>

<main class="homepage" role="main">
    <div class="main-content">
      
      <div class="hero-section">
        
        <div class="col-left-sidebar">
          <?php include BASE_PATH . '/includes/User/sidebar.php'; ?>
        </div>

        <div class="hero-content">
             <div class="main-slider-wrapper">
                 <button class="slider-btn btn-prev" onclick="scrollSlider('main-banner-slider', 'left')">
                    <i class="fa-solid fa-chevron-left"></i>
                 </button>

                 <div class="product-slider full-width-slider" id="main-banner-slider">
                    <a href="#" class="main-banner-slide"><img src="public/assets/images/main_banner1.jpg" alt="Banner 1"></a>
                    <a href="#" class="main-banner-slide"><img src="public/assets/images/main_banner2.jpg" alt="Banner 2"></a>
                    <a href="#" class="main-banner-slide"><img src="public/assets/images/main_banner3.jpg" alt="Banner 3"></a>
                 </div>

                 <button class="slider-btn btn-next" onclick="scrollSlider('main-banner-slider', 'right')">
                    <i class="fa-solid fa-chevron-right"></i>
                 </button>
              </div>
              
              <div class="deal-banners" style="margin-top: 20px;">
                <div class="banner-item"><a href="#"><img src="public/assets/images/deal1.jpg" alt="Deal 1"></a></div>
                <div class="banner-item"><a href="#"><img src="public/assets/images/deal2.jpg" alt="Deal 2"></a></div>
                <div class="banner-item"><a href="#"><img src="public/assets/images/deal3.jpg" alt="Deal 3"></a></div>
                <div class="banner-item"><a href="#"><img src="public/assets/images/deal4.jpg" alt="Deal 4"></a></div>
              </div>
        </div>
      </div>
      <div class="full-width-content">
          
          <section class="product-section">
            <div class="section-head">
              <h2>Laptop Bán Chạy</h2>
              <a class="view-all" href="public/user/product.php?cate=laptop">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-laptop', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-laptop">
                  <?php foreach ($list_laptop as $row): ?>
                      <div class="product-card">
                          <a href="public/user/product_detail.php?id=<?= $row['id'] ?>" style="display:block; text-decoration:none; color:inherit;">
                              <div style="position:relative; margin-bottom:10px;">
                                  <?php if($row['label']): ?><span class="product-label"><?= $row['label'] ?></span><?php endif; ?>
                                  <img loading="lazy" src="public/assets/images/<?= $row['image'] ?>" onerror="this.src='https://via.placeholder.com/300x300?text=Laptop'" alt="<?= htmlspecialchars($row['name']) ?>">
                              </div>
                              <h3 style="font-size:14px; height:38px; overflow:hidden; margin-bottom:5px;"><?= htmlspecialchars($row['name']) ?></h3>
                              <div style="color:#d70018; font-weight:700;"><?= number_format($row['price'], 0, ',', '.') ?>₫</div>
                          </a>
                          <form action="public/user/cart.php" method="POST" style="margin-top:10px;">
                              <input type="hidden" name="action" value="add">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>">
                              <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                              <input type="hidden" name="price" value="<?= $row['price'] ?>">
                              <button type="submit" style="width:100%; padding:8px; background:#d70018; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;">Thêm giỏ</button>
                          </form>
                      </div>
                  <?php endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-laptop', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>

          <section class="product-section">
            <div class="section-head">
              <h2>PC GVN - Máy bộ</h2>
              <a class="view-all" href="public/user/product.php?cate=pc">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-pc', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-pc">
                  <?php foreach ($list_pc as $row): ?>
                      <div class="product-card">
                          <a href="public/user/product_detail.php?id=<?= $row['id'] ?>" style="display:block; text-decoration:none; color:inherit;">
                              <div style="position:relative; margin-bottom:10px;">
                                  <?php if($row['label']): ?><span class="product-label"><?= $row['label'] ?></span><?php endif; ?>
                                  <img loading="lazy" src="public/assets/images/<?= $row['image'] ?>" onerror="this.src='https://via.placeholder.com/300x300?text=PC'" alt="<?= htmlspecialchars($row['name']) ?>">
                              </div>
                              <h3 style="font-size:14px; height:38px; overflow:hidden; margin-bottom:5px;"><?= htmlspecialchars($row['name']) ?></h3>
                              <div style="color:#d70018; font-weight:700;"><?= number_format($row['price'], 0, ',', '.') ?>₫</div>
                          </a>
                          <form action="public/user/cart.php" method="POST" style="margin-top:10px;">
                              <input type="hidden" name="action" value="add">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>">
                              <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                              <input type="hidden" name="price" value="<?= $row['price'] ?>">
                              <button type="submit" style="width:100%; padding:8px; background:#d70018; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;">Thêm giỏ</button>
                          </form>
                      </div>
                  <?php endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-pc', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>

          <section class="product-section">
            <div class="section-head">
              <h2>Gaming Gear</h2>
              <a class="view-all" href="public/user/product.php?cate=gear">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-gear', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-gear">
                  <?php foreach ($list_gear as $row): ?>
                      <div class="product-card">
                          <a href="public/user/product_detail.php?id=<?= $row['id'] ?>" style="display:block; text-decoration:none; color:inherit;">
                              <div style="position:relative; margin-bottom:10px;">
                                  <?php if($row['label']): ?><span class="product-label"><?= $row['label'] ?></span><?php endif; ?>
                                  <img loading="lazy" src="public/assets/images/<?= $row['image'] ?>" onerror="this.src='https://via.placeholder.com/300x300?text=Gear'" alt="<?= htmlspecialchars($row['name']) ?>">
                              </div>
                              <h3 style="font-size:14px; height:38px; overflow:hidden; margin-bottom:5px;"><?= htmlspecialchars($row['name']) ?></h3>
                              <div style="color:#d70018; font-weight:700;"><?= number_format($row['price'], 0, ',', '.') ?>₫</div>
                          </a>
                          <form action="public/user/cart.php" method="POST" style="margin-top:10px;">
                              <input type="hidden" name="action" value="add">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>">
                              <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                              <input type="hidden" name="price" value="<?= $row['price'] ?>">
                              <button type="submit" style="width:100%; padding:8px; background:#d70018; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;">Thêm giỏ</button>
                          </form>
                      </div>
                  <?php endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-gear', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          
          <section class="product-section">
              <div class="section-head">
                  <h2>Chuyên trang khuyến mãi</h2>
                  <a href="#" class="view-all">Xem tất cả</a>
              </div>
              <div class="promo-grid">
                  <a href="#" class="promo-item"><img src="https://via.placeholder.com/400x200/007bff/fff?text=Khuyen+Mai+1" alt="Promo 1"></a>
                  <a href="#" class="promo-item"><img src="https://via.placeholder.com/400x200/28a745/fff?text=Khuyen+Mai+2" alt="Promo 2"></a>
                  <a href="#" class="promo-item"><img src="https://via.placeholder.com/400x200/dc3545/fff?text=Khuyen+Mai+3" alt="Promo 3"></a>
              </div>
          </section>

          <section class="product-section">
              <div class="section-head">
                  <h2>Tin tức công nghệ</h2>
                  <a href="#" class="view-all">Xem tất cả</a>
              </div>
              <div class="news-grid">
                  <?php foreach($news_list as $news): ?>
                      <div class="news-item">
                          <a href="#">
                              <img src="<?= $news['img'] ?>" alt="<?= $news['title'] ?>">
                              <h3><?= $news['title'] ?></h3>
                          </a>
                      </div>
                  <?php endforeach; ?>
              </div>
          </section>

          <section class="product-section brand-section">
            <div class="section-head"><h2>THƯƠNG HIỆU NỔI BẬT</h2></div>
            <div class="brand-grid">
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=ASUS" alt="ASUS"></a></div>
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=GIGABYTE" alt="GIGABYTE"></a></div>
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=MSI" alt="MSI"></a></div>
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=ACER" alt="ACER"></a></div>
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=RAZER" alt="RAZER"></a></div>
               <div class="brand-item"><a href="#"><img src="https://placehold.co/120x60/fff/333?text=CORSAIR" alt="CORSAIR"></a></div>
            </div>
          </section>

          <section class="quick-categories-section">
            <div class="quick-categories-header">Danh mục sản phẩm</div>
                <div class="quick-categories-grid">
                    <?php foreach($quick_categories as $cat): ?>
                        <div class="quick-cat-item">
                            <a href="public/user/product.php?cate=<?= $cat['code'] ?>">
                                <i class="<?= $cat['icon'] ?>"></i>
                                <span><?= $cat['name'] ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

      </div>
      </div>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>