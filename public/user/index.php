<?php
// =========================
// 1. CẤU HÌNH ĐƯỜNG DẪN
// =========================
define('BASE_PATH', dirname(__DIR__));

// =========================
// 2. KẾT NỐI DATABASE
// =========================
$db_path_mvc = __DIR__ . '/../../app/config/database.php';
$db_path_inc = BASE_PATH . '/includes/db.php';

if (file_exists($db_path_mvc)) {
    require_once $db_path_mvc;
} elseif (file_exists($db_path_inc)) {
    require_once $db_path_inc;
} else {
    die("Lỗi: Không tìm thấy file cấu hình Database.");
}

try {
    $db   = new Database();
    /** @var mysqli $conn */
    $conn = $db->getConnection();
} catch (Exception $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$PAGE_TITLE  = 'Trang chủ';
$SHOW_SEARCH = true;

/**
 * 3. HÀM LẤY SẢN PHẨM THEO DANH MỤC
 */
function get_products_by_category(mysqli $conn, array $category_ids, int $limit = 10): array
{
    if (empty($category_ids)) {
        return [];
    }

    $category_ids = array_map('intval', $category_ids);
    $ids_string   = implode(',', $category_ids);
    $limit        = max(1, (int)$limit);

    $sql = "
        SELECT *
        FROM san_pham
        WHERE id_dm IN ($ids_string)
          AND trang_thai = 1
        ORDER BY luot_xem DESC, ngay_nhap DESC
        LIMIT $limit
    ";

    if (!$result = $conn->query($sql)) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * 4. HÀM TÍNH LABEL GIẢM GIÁ
 */
function get_discount_label($price, $sale_price): string
{
    $price      = (float)$price;
    $sale_price = (float)$sale_price;

    if ($sale_price > 0 && $sale_price < $price) {
        $percent = round((($price - $sale_price) / $price) * 100);
        return "-{$percent}%";
    }
    return '';
}

// =========================
// 5. LẤY DATA THẬT TỪ DB
// =========================

// Laptop (ID 1: Gaming, 2: Văn phòng)
$list_laptop = get_products_by_category($conn, [1, 2], 12);
// PC (ID 3)
$list_pc = get_products_by_category($conn, [3], 12);
// Màn hình (ID 4)
$list_monitor = get_products_by_category($conn, [4], 12);
// Gear: Phím(5), Chuột(6), Tai nghe(7)
$list_gear = get_products_by_category($conn, [5, 6, 7], 12);
// Linh kiện PC: RAM(9), VGA(10), CPU(11), Main(12)
$list_components = get_products_by_category($conn, [9, 10, 11, 12], 12);
// Phụ kiện: Loa(17), Webcam(13), Phụ kiện phone(19), Thiết bị đeo(20)
$list_accessories = get_products_by_category($conn, [13, 17, 19, 20], 12);

// =========================
// 6. DATA DANH MỤC NHANH
// =========================
$quick_categories = [
    ['name' => 'Laptop',        'code' => 'laptop',      'icon' => 'fa-solid fa-laptop'],
    ['name' => 'PC',            'code' => 'pc',          'icon' => 'fa-solid fa-desktop'],
    ['name' => 'Màn hình',      'code' => 'monitor',     'icon' => 'fa-solid fa-tv'],
    ['name' => 'Mainboard',     'code' => 'mainboard',   'icon' => 'fa-solid fa-microchip'],
    ['name' => 'CPU',           'code' => 'cpu',         'icon' => 'fa-solid fa-memory'],
    ['name' => 'VGA',           'code' => 'vga',         'icon' => 'fa-solid fa-ticket'],
    ['name' => 'RAM',           'code' => 'ram',         'icon' => 'fa-solid fa-layer-group'],
    ['name' => 'Ổ cứng',        'code' => 'hdd',         'icon' => 'fa-solid fa-hard-drive'],
    ['name' => 'Case',          'code' => 'case',        'icon' => 'fa-solid fa-server'],
    ['name' => 'Tản nhiệt',     'code' => 'cooling',     'icon' => 'fa-solid fa-snowflake'],
    ['name' => 'Nguồn',         'code' => 'psu',         'icon' => 'fa-solid fa-plug'],
    ['name' => 'Bàn phím',      'code' => 'keyboard',    'icon' => 'fa-regular fa-keyboard'],
    ['name' => 'Chuột',         'code' => 'mouse',       'icon' => 'fa-solid fa-computer-mouse'],
    ['name' => 'Ghế',           'code' => 'chair',       'icon' => 'fa-solid fa-chair'],
    ['name' => 'Tai nghe',      'code' => 'headset',     'icon' => 'fa-solid fa-headset'],
    ['name' => 'Loa',           'code' => 'speaker',     'icon' => 'fa-solid fa-volume-high'],
    ['name' => 'Console',       'code' => 'console',     'icon' => 'fa-solid fa-gamepad'],
    ['name' => 'Phụ kiện',      'code' => 'accessories', 'icon' => 'fa-brands fa-usb'],
    ['name' => 'Thiết bị VP',   'code' => 'office',      'icon' => 'fa-solid fa-print'],
    ['name' => 'Sạc DP',        'code' => 'powerbank',   'icon' => 'fa-solid fa-battery-full'],
];

// =========================
// 7. THÊM CSS/JS RIÊNG CHO TRANG NÀY VÀO FOOTER
// =========================
ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/index.css?v=10005">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="public/assets/js/indexUser.js?v=9999" defer></script>

<style>
    /* Layout hero 2 cột */
    .hero-section {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }
    .full-width-content { width: 100%; }

    @media (max-width: 991px) {
        .hero-section { grid-template-columns: 1fr; }
        .col-left-sidebar { display: none; }
    }

    /* Tăng tương phản dark mode */
    [data-theme="dark"] .product-card h3 {
        color: #fff !important;
        font-weight: 600;
    }
    [data-theme="dark"] .section-head h2 {
        color: #fff !important;
        text-shadow: 0 0 10px rgba(255,255,255,0.2);
    }

    /* ====== INDEX – CARD DETAIL, RATING & CHIP CẤU HÌNH ====== */
    .product-card .product-thumb-wrap {
        position: relative;
        width: 100%;
        height: 210px;
        padding: 10px 10px 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-card .product-thumb-wrap img {
        max-height: 190px;
        max-width: 100%;
        width: auto;
        object-fit: contain;
        display: block;
    }

    .product-card .product-title {
        font-size: 14px;
        font-weight: 600;
        margin: 4px 0 6px;
        height: 40px;
        overflow: hidden;
    }

    .product-card .price-block {
        margin-bottom: 6px;
    }

    .product-config-row {
        background: rgba(148, 163, 184, 0.15);
        border-radius: 8px;
        padding: 6px 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 8px;
        font-size: 12px;
    }
    .config-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 6px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.05);
        color: #64748b;
        white-space: nowrap;
    }

    .rating-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .rating-row .stars i {
        color: #fbbf24;
        font-size: 12px;
        margin-right: 1px;
    }

    .card-actions {
        display: flex;
        gap: 6px;
        margin-top: 4px;
    }
    .card-actions .add-cart-btn {
        font-size: 14px;
        padding: 10px 0;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        min-width: 120px;
    }
    .card-actions .add-cart-btn--primary {
        background: linear-gradient(135deg, #22c55e, #4ade80);
        flex: 1.3;
        color: #0f172a;
        box-shadow: 0 0 0 1px rgba(34,197,94,.4),
                    0 8px 18px rgba(34,197,94,.35);
    }
    .card-actions .add-cart-btn--secondary {
        flex: 1;
        background: #0f172a;
        color: #e5e7eb;
        box-shadow: 0 0 0 1px rgba(15,23,42,.5);
    }
    .card-actions .add-cart-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(15,23,42,.45);
    }

    [data-theme="dark"] .product-config-row {
        background: rgba(15, 23, 42, 0.9);
    }
</style>
<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean();

// HEADER
include BASE_PATH . '/includes/User/header.php';
?>

<!-- Banner dọc 2 bên -->
<div class="sticky-banner-container-left">
    <a href="#"><img loading="lazy" src="public/assets/images/banner_doc_1.jpg" alt="Banner Trái"></a>
</div>
<div class="sticky-banner-container-right">
    <a href="#"><img loading="lazy" src="public/assets/images/banner_doc_2.jpg" alt="Banner Phải"></a>
</div>

<main class="homepage" role="main">
    <div class="main-content">

        <!-- ========== HERO: SIDEBAR + SLIDER ========== -->
        <div class="hero-section">
            <aside class="col-left-sidebar" aria-label="Danh mục sản phẩm">
                <?php include BASE_PATH . '/includes/User/sidebar.php'; ?>
            </aside>

            <div class="hero-content">
                <div class="main-slider-wrapper">
                    <button class="slider-btn btn-prev" type="button"
                            aria-label="Slide trước"
                            onclick="scrollSlider('main-banner-slider', 'left')">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <div class="product-slider full-width-slider" id="main-banner-slider">
                        <a href="#" class="main-banner-slide">
                            <img src="public/assets/images/main_banner1.jpg" alt="Banner 1">
                        </a>
                        <a href="#" class="main-banner-slide">
                            <img src="public/assets/images/main_banner2.jpg" alt="Banner 2">
                        </a>
                        <a href="#" class="main-banner-slide">
                            <img src="public/assets/images/main_banner3.jpg" alt="Banner 3">
                        </a>
                    </div>

                    <button class="slider-btn btn-next" type="button"
                            aria-label="Slide tiếp"
                            onclick="scrollSlider('main-banner-slider', 'right')">
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

        <!-- ========== CÁC SECTION SẢN PHẨM ========== -->
        <div class="full-width-content">

            <?php if (!empty($list_laptop)): ?>
                <?php renderProductSection('slider-laptop', 'Laptop Bán Chạy', 'laptop', $list_laptop); ?>
            <?php endif; ?>

            <?php if (!empty($list_pc)): ?>
                <?php renderProductSection('slider-pc', 'PC GVN - Máy bộ', 'pc', $list_pc); ?>
            <?php endif; ?>

            <?php if (!empty($list_monitor)): ?>
                <?php renderProductSection('slider-monitor', 'Màn hình giá tốt', 'monitor', $list_monitor); ?>
            <?php endif; ?>

            <?php if (!empty($list_components)): ?>
                <?php renderProductSection('slider-components', 'Linh kiện máy tính', 'components', $list_components); ?>
            <?php endif; ?>

            <?php if (!empty($list_gear)): ?>
                <?php renderProductSection('slider-gear', 'Gaming Gear', 'gear', $list_gear); ?>
            <?php endif; ?>

            <?php if (!empty($list_accessories)): ?>
                <?php renderProductSection('slider-accessories', 'Phụ kiện công nghệ', 'accessories', $list_accessories); ?>
            <?php endif; ?>

            <!-- Thương hiệu nổi bật -->
            <section class="product-section brand-section">
              <div class="section-head">
                <h2>THƯƠNG HIỆU NỔI BẬT</h2>
              </div>
              <div class="brand-grid">
                <div class="brand-item"><span>ASUS</span></div>
                <div class="brand-item"><span>GIGABYTE</span></div>
                <div class="brand-item"><span>MSI</span></div>
                <div class="brand-item"><span>ACER</span></div>
                <div class="brand-item"><span>RAZER</span></div>
                <div class="brand-item"><span>CORSAIR</span></div>
              </div>
            </section>

            <!-- Danh mục nhanh -->
            <section class="quick-categories-section">
                <div class="quick-categories-header">Danh mục sản phẩm</div>
                <div class="quick-categories-grid">
                    <?php foreach ($quick_categories as $cat): ?>
                        <div class="quick-cat-item">
                            <a href="public/products?cate=<?= htmlspecialchars($cat['code'], ENT_QUOTES) ?>">
                                <i class="<?= htmlspecialchars($cat['icon'], ENT_QUOTES) ?>"></i>
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>
    </div>
</main>

<?php
// =========================
// 8. HELPER RENDER SẢN PHẨM & SECTION
// =========================

/**
 * Card sản phẩm dùng trên TRANG CHỦ
 */
function renderProductCard($row)
{
    $price      = (float)$row['gia'];
    $sale_price = (float)$row['gia_khuyen_mai'];
    $price_show = ($sale_price > 0 && $sale_price < $price) ? $sale_price : $price;
    $has_sale   = ($sale_price > 0 && $sale_price < $price);
    $label      = get_discount_label($price, $sale_price);
    $img_url    = "public/assets/images/" . $row['hinh_anh'];

    // Tách mo_ta_ngan thành các chip cấu hình
    $configChips = [];
    if (!empty($row['mo_ta_ngan'])) {
        $parts = preg_split('/[,•\-]/u', $row['mo_ta_ngan']);
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p !== '') {
                $configChips[] = $p;
            }
            if (count($configChips) >= 4) break;
        }
    }

    // Rating giả từ lượt xem
    $views       = isset($row['luot_xem']) ? (int)$row['luot_xem'] : 0;
    $ratingScore = 4.2;
    if ($views > 0) {
        $ratingScore = 4.2 + min(0.7, $views / 6000);
    }
    $ratingScore = number_format($ratingScore, 1);
    $ratingCount = max(5, (int)floor($views / 8));
    ?>
    <div class="product-card">
        <button class="btn-wishlist" data-id="<?= (int)$row['id'] ?>" title="Thêm vào yêu thích">
            <i class="fa-solid fa-heart"></i>
        </button>

        <a href="public/products/<?= (int)$row['id'] ?>" style="text-decoration:none; color:inherit;">
            <div class="product-thumb-wrap">
                <?php if ($label): ?>
                    <span class="product-label"><?= $label ?></span>
                <?php endif; ?>
                <img loading="lazy"
                     src="<?= htmlspecialchars($img_url, ENT_QUOTES) ?>"
                     onerror="this.src='https://via.placeholder.com/300x300?text=TechShop'"
                     alt="<?= htmlspecialchars($row['ten_sp'], ENT_QUOTES) ?>">
            </div>

            <h3 class="product-title">
                <?= htmlspecialchars($row['ten_sp'], ENT_QUOTES) ?>
            </h3>

            <div class="price-block">
                <span class="price"><?= number_format($price_show, 0, ',', '.') ?>₫</span>
                <?php if ($has_sale): ?>
                    <span class="price-old"><?= number_format($price, 0, ',', '.') ?>₫</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($configChips)): ?>
                <div class="product-config-row">
                    <?php foreach ($configChips as $chip): ?>
                        <span class="config-chip">
                            <?= htmlspecialchars($chip, ENT_QUOTES) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($ratingCount >= 5): ?>
                <div class="rating-row">
                    <div class="stars" aria-hidden="true">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                    </div>
                    <span><?= $ratingScore ?>/5</span>
                    <span>(<?= $ratingCount ?> đánh giá)</span>
                </div>
            <?php endif; ?>
        </a>

        <div class="card-actions">
            <!-- THÊM GIỎ HÀNG – đã đổi name field đúng theo cart.php -->
            <form action="public/cart" method="POST" style="flex:1;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id_san_pham" value="<?= (int)$row['id'] ?>">
                <input type="hidden" name="so_luong" value="1">
                <button type="submit" class="add-cart-btn add-cart-btn--primary">
                    Thêm giỏ
                </button>
            </form>

            <!-- XEM CHI TIẾT – nút riêng như bạn yêu cầu -->
            <a href="public/products/<?= (int)$row['id'] ?>"
               class="add-cart-btn add-cart-btn--secondary">
                Xem chi tiết
            </a>
        </div>
    </div>
    <?php
}

/**
 * Render 1 section slider sản phẩm
 */
function renderProductSection(string $sliderId, string $title, string $cateCode, array $list): void
{
    ?>
    <section class="product-section">
        <div class="section-head">
            <h2><?= htmlspecialchars($title, ENT_QUOTES) ?></h2>
            <a class="view-all"
               href="public/products?cate=<?= htmlspecialchars($cateCode, ENT_QUOTES) ?>">
                Xem tất cả
            </a>
        </div>
        <div class="product-slider-wrapper">
            <button class="slider-btn btn-prev" type="button"
                    onclick="scrollSlider('<?= $sliderId ?>', 'left')">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="product-slider" id="<?= htmlspecialchars($sliderId, ENT_QUOTES) ?>">
                <?php foreach ($list as $row): ?>
                    <?php renderProductCard($row); ?>
                <?php endforeach; ?>
            </div>

            <button class="slider-btn btn-next" type="button"
                    onclick="scrollSlider('<?= $sliderId ?>', 'right')">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>
    <?php
}

// FOOTER
include BASE_PATH . '/includes/User/footer.php';
