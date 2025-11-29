<?php
// 1. CẤU HÌNH ĐƯỜNG DẪN
define('BASE_PATH', dirname(__DIR__));

// 2. KẾT NỐI DATABASE (Theo cấu trúc MVC hoặc Includes)
$db_path_mvc = __DIR__ . '/../../app/config/database.php';
$db_path_inc = BASE_PATH . '/includes/db.php';

if (file_exists($db_path_mvc)) {
    require_once $db_path_mvc;
} elseif (file_exists($db_path_inc)) {
    require_once $db_path_inc;
} else {
    die("Lỗi: Không tìm thấy file cấu hình Database.");
}

// Khởi tạo kết nối
try {
    $db = new Database();
    $conn = $db->getConnection();
} catch (Exception $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$PAGE_TITLE = 'Trang chủ';
$SHOW_SEARCH = true;

/**
 * 3. HÀM LẤY SẢN PHẨM TỪ DB
 * Lấy sản phẩm mới nhất hoặc bán chạy (dựa trên lượt xem/tồn kho)
 */
function get_products_by_category($conn, $category_ids, $limit = 10) {
    $ids_string = implode(',', $category_ids);
    // Lấy sản phẩm đang bán, sắp xếp theo lượt xem (giả lập bán chạy) hoặc mới nhất
    $sql = "SELECT * FROM san_pham 
            WHERE id_dm IN ($ids_string) AND trang_thai = 1 
            ORDER BY luot_xem DESC, ngay_nhap DESC 
            LIMIT $limit";  
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * 4. HÀM TÍNH PHẦN TRĂM GIẢM GIÁ
 */
function get_discount_label($price, $sale_price) {
    if ($sale_price > 0 && $sale_price < $price) {
        $percent = round((($price - $sale_price) / $price) * 100);
        return "-{$percent}%";
    }
    return '';
}

// 5. LẤY DỮ LIỆU THẬT TỪ DB (Mapping theo techshop.sql)
// Laptop (ID 1: Gaming, 2: Văn phòng)
$list_laptop = get_products_by_category($conn, [1, 2], 12);

// PC (ID 3)
$list_pc = get_products_by_category($conn, [3], 12);

// Màn hình (ID 4) - Mới thêm
$list_monitor = get_products_by_category($conn, [4], 12);

// Gear: Phím(5), Chuột(6), Tai nghe(7)
$list_gear = get_products_by_category($conn, [5, 6, 7], 12);

// Linh kiện PC: RAM(9), VGA(10), CPU(11), Main(12), Nguồn(15? - Check lại ID nguồn)
$list_components = get_products_by_category($conn, [9, 10, 11, 12], 12);

// Phụ kiện: Loa(17), Webcam(13), Cáp/Sạc...
$list_accessories = get_products_by_category($conn, [13, 17, 19, 20], 12);


// 6. DATA DANH MỤC NHANH (Giữ nguyên để điều hướng)
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

ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/index.css?v=10005">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="public/assets/js/user.js?v=9999"></script>
<script src="public/assets/js/indexUser.js?v=9999" defer></script> 

<style>
    /* CSS Cục bộ: Layout 2 cột */
    .hero-section { display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 40px; }
    .full-width-content { display: block; width: 100%; }
    @media (max-width: 991px) {
        .hero-section { grid-template-columns: 1fr; }
        .col-left-sidebar { display: none; }
    }
    
    /* Fix chữ nổi bật trong Dark Mode (Thêm vào đây cho chắc) */
    [data-theme="dark"] .product-card h3 { color: #fff !important; font-weight: 600; }
    [data-theme="dark"] .section-head h2 { color: #fff !important; text-shadow: 0 0 10px rgba(255,255,255,0.2); }
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
          
          <?php if(!empty($list_laptop)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>Laptop Bán Chạy</h2>
              <a class="view-all" href="public/user/product.php?cate=laptop">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-laptop', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-laptop">
                  <?php foreach ($list_laptop as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-laptop', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>

          <?php if(!empty($list_pc)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>PC GVN - Máy bộ</h2>
              <a class="view-all" href="public/user/product.php?cate=pc">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-pc', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-pc">
                  <?php foreach ($list_pc as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-pc', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>
          
          <?php if(!empty($list_monitor)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>Màn hình giá tốt</h2>
              <a class="view-all" href="public/user/product.php?cate=monitor">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-monitor', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-monitor">
                  <?php foreach ($list_monitor as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-monitor', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>

          <?php if(!empty($list_components)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>Linh kiện máy tính</h2>
              <a class="view-all" href="public/user/product.php?cate=components">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-components', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-components">
                  <?php foreach ($list_components as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-components', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>

          <?php if(!empty($list_gear)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>Gaming Gear</h2>
              <a class="view-all" href="public/user/product.php?cate=gear">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-gear', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-gear">
                  <?php foreach ($list_gear as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-gear', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>
          
          <?php if(!empty($list_accessories)): ?>
          <section class="product-section">
            <div class="section-head">
              <h2>Phụ kiện công nghệ</h2>
              <a class="view-all" href="public/user/product.php?cate=accessories">Xem tất cả</a>
            </div>
            <div class="product-slider-wrapper">
                <button class="slider-btn btn-prev" onclick="scrollSlider('slider-accessories', 'left')"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="product-slider" id="slider-accessories">
                  <?php foreach ($list_accessories as $row): renderProductCard($row); endforeach; ?>
                </div>
                <button class="slider-btn btn-next" onclick="scrollSlider('slider-accessories', 'right')"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
          </section>
          <?php endif; ?>

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
// Helper Function để render thẻ sản phẩm (DRY code)
function renderProductCard($row) {
    $price = $row['gia'];
    $sale_price = $row['gia_khuyen_mai'];
    $price_show = ($sale_price > 0 && $sale_price < $price) ? $sale_price : $price;
    $label = get_discount_label($price, $sale_price);
    $img_url = "public/assets/images/" . $row['hinh_anh'];
    ?>
    <div class="product-card">
        <button class="btn-wishlist" data-id="<?= $row['id'] ?>" title="Thêm vào yêu thích">
            <i class="fa-solid fa-heart"></i>
        </button>

        <a href="public/user/product_detail.php?id=<?= $row['id'] ?>" style="display:block; text-decoration:none; color:inherit;">
            <div style="position:relative; margin-bottom:10px;">
                <?php if($label): ?><span class="product-label"><?= $label ?></span><?php endif; ?>
                <img loading="lazy" src="<?= htmlspecialchars($img_url) ?>" onerror="this.src='https://via.placeholder.com/300x300?text=TechShop'" alt="<?= htmlspecialchars($row['ten_sp']) ?>">
            </div>
            <h3 style="font-size:14px; height:38px; overflow:hidden; margin-bottom:5px;"><?= htmlspecialchars($row['ten_sp']) ?></h3>
            <div style="color:#d70018; font-weight:700;" class="price"><?= number_format($price_show, 0, ',', '.') ?>₫</div>
            <?php if($sale_price > 0 && $sale_price < $price): ?>
            <div style="font-size:12px; text-decoration:line-through; color:#999;"><?= number_format($price, 0, ',', '.') ?>₫</div>
            <?php endif; ?>
        </a>
        <form action="public/user/cart.php" method="POST" style="margin-top:10px;">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" style="width:100%; padding:8px; background:#d70018; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;">Thêm giỏ</button>
        </form>
    </div>
    <?php
}

include BASE_PATH . '/includes/User/footer.php';
?>