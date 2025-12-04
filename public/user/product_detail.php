<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// BASE_PATH trỏ về thư mục gốc project: C:\xampp\htdocs\TechShop
define('BASE_PATH', dirname(__DIR__, 1));

// =============== KẾT NỐI DATABASE ===============
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'techshop';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Kết nối database thất bại: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// =============== LẤY ID SẢN PHẨM ===============
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Sản phẩm không hợp lệ');
}

// Tăng lượt xem (không quan trọng nếu lỗi)
$conn->query("UPDATE san_pham SET luot_xem = luot_xem + 1 WHERE id = {$id}");

// Lấy chi tiết sản phẩm + tên danh mục
$sql = "
    SELECT sp.*, dm.ten_dm
    FROM san_pham sp
    JOIN danh_muc dm ON sp.id_dm = dm.id
    WHERE sp.id = {$id} AND sp.trang_thai = 1
    LIMIT 1
";
$rs = $conn->query($sql);
if (!$rs || $rs->num_rows == 0) {
    die('Không tìm thấy sản phẩm');
}
$p = $rs->fetch_assoc();

// =============== HÀM XỬ LÝ ĐƯỜNG DẪN ẢNH ===============
function build_product_image_url(array $row): string
{
    $path = trim($row['hinh_anh'] ?? '');

    if ($path === '') {
        // ảnh fallback
        return 'public/assets/images/TechShop.jpg';
    }

    // Nếu là URL tuyệt đối
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    // Nếu đã có prefix public/
    if (strpos($path, 'public/') === 0) {
        return $path;
    }

    // Nếu bắt đầu bằng uploads/
    if (strpos($path, 'uploads/') === 0) {
        return 'public/' . $path;
    }

    // Mặc định: lưu file ở public/uploads/products
    return 'public/uploads/products/' . $path;
}

// Chuẩn bị dữ liệu
$name      = $p['ten_sp'];
$gia       = (float)$p['gia'];
$sale      = $p['gia_khuyen_mai'] !== null ? (float)$p['gia_khuyen_mai'] : null;
$display   = ($sale !== null && $sale > 0 && $sale < $gia) ? $sale : $gia;
$oldPrice  = ($sale !== null && $sale > 0 && $sale < $gia) ? $gia : 0;

$discountPercent = 0;
if ($oldPrice > 0) {
    $discountPercent = (int)round(100 - $display * 100 / $oldPrice);
}

// Ảnh chính
$thumb = build_product_image_url($p);

$moTaNgan = $p['mo_ta_ngan'];
$chiTiet  = $p['chi_tiet'];
$soLuong  = (int)$p['so_luong_ton'];
$tenDm    = $p['ten_dm'];
$view     = (int)$p['luot_xem'];

// text trạng thái
$stockText  = $soLuong > 0 ? "Còn hàng" : "Hết hàng";
$stockClass = $soLuong > 0 ? "pdp-stock-text--ok" : "pdp-stock-text--out";

// tách mô tả ngắn thành từng dòng bullet nếu có xuống dòng
$featureLines = [];
if ($moTaNgan) {
    $tmp = preg_split('/\r\n|\r|\n/', trim($moTaNgan));
    foreach ($tmp as $line) {
        $line = trim($line);
        if ($line !== '') {
            $featureLines[] = $line;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($name, ENT_QUOTES) ?> | TechShop</title>

    <!-- base giống các trang FE khác -->
    <base href="/TechShop/">

    <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=1">
    <link rel="stylesheet" href="public/assets/css/cssUser/product_detail.css?v=1">
</head>
<body>
<?php @include BASE_PATH . '/includes/User/header.php'; ?>

<main class="main-content">
    <div class="pdp-page">

        <!-- CỘT TRÁI: ẢNH + META -->
        <div class="pdp-left">
            <div class="pdp-media-card">
                <div class="pdp-image-main">
                    <img
                        src="<?= htmlspecialchars($thumb, ENT_QUOTES) ?>"
                        alt="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                        onerror="this.src='https://via.placeholder.com/400x400?text=TechShop';"
                    >
                </div>

                <div class="pdp-meta-strip">
                    <div class="pdp-meta-pill">
                        <span>Danh mục</span>
                        <span><?= htmlspecialchars($tenDm, ENT_QUOTES) ?></span>
                    </div>
                    <div class="pdp-meta-pill">
                        <span>Mã sản phẩm</span>
                        <span>#<?= (int)$p['id'] ?></span>
                    </div>
                    <div class="pdp-meta-pill">
                        <span>Lượt xem</span>
                        <span><?= $view ?></span>
                    </div>
                    <div class="pdp-meta-pill">
                        <span>Tình trạng</span>
                        <span><?= $stockText ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: THÔNG TIN CHI TIẾT -->
        <div class="pdp-right">
            <h1 class="pdp-info-title"><?= htmlspecialchars($name, ENT_QUOTES) ?></h1>

            <div class="pdp-badges-row">
                <?php if ($discountPercent > 0): ?>
                    <span class="pdp-badge pdp-badge--sale">Giảm <?= $discountPercent ?>%</span>
                <?php endif; ?>
                <span class="pdp-badge">Chính hãng</span>
                <span class="pdp-badge">Hỗ trợ lắp đặt</span>
                <span class="pdp-badge">Đổi trả 7 ngày</span>
                <?php if ($soLuong > 0): ?>
                    <span class="pdp-badge pdp-badge--stock-ok">Còn hàng</span>
                <?php else: ?>
                    <span class="pdp-badge pdp-badge--stock-out">Tạm hết hàng</span>
                <?php endif; ?>
            </div>

            <!-- CARD GIÁ -->
            <div class="pdp-price-card">
                <div class="pdp-price-main">
                    <div class="pdp-price">
                        <?= number_format($display, 0, ',', '.') ?> ₫
                    </div>
                    <?php if ($oldPrice > 0): ?>
                        <div class="pdp-old-price-line">
                            <span class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?> ₫</span>
                            <span>| Giảm <?= $discountPercent ?>%</span>
                        </div>
                    <?php endif; ?>
                    <div class="pdp-price-note">
                        Giá đã bao gồm VAT (nếu có). Vui lòng liên hệ nhân viên tư vấn để nhận báo giá tốt nhất.
                    </div>
                </div>

                <div class="pdp-price-side">
                    <div><strong>Ưu đãi khi mua tại TechShop:</strong></div>
                    <div>- Hỗ trợ lắp ráp PC tại cửa hàng</div>
                    <div>- Tư vấn cấu hình phù hợp nhu cầu &amp; ngân sách</div>
                    <div>- Bảo hành theo chính sách nhà sản xuất</div>
                </div>
            </div>

            <!-- HỘP MUA HÀNG -->
            <div class="pdp-purchase-box">
                <!-- dùng route /cart thay vì gọi trực tiếp file -->
                <form method="post" action="public/cart" class="pdp-form">
                    <label for="qty">Số lượng</label>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id_san_pham" value="<?= (int)$p['id'] ?>">
                    <input
                        type="number"
                        id="qty"
                        name="so_luong"
                        min="1"
                        max="<?= max(1, $soLuong) ?>"
                        value="1"
                    >
                    <button type="submit" class="btn">
                        Thêm vào giỏ
                    </button>
                    <span class="pdp-stock-text <?= $stockClass ?>">
                        <?= $soLuong > 0 ? "Còn lại: {$soLuong} sản phẩm" : "Hết hàng, vui lòng liên hệ" ?>
                    </span>
                </form>
            </div>

            <!-- MÔ TẢ NGẮN / FEATURE BULLETS -->
            <?php if (!empty($featureLines)): ?>
                <div class="pdp-desc-block">
                    <div class="pdp-desc-title">Điểm nổi bật</div>
                    <ul class="pdp-feature-list">
                        <?php foreach ($featureLines as $line): ?>
                            <li><?= htmlspecialchars($line, ENT_QUOTES) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($moTaNgan): ?>
                <div class="pdp-desc-block">
                    <div class="pdp-desc-title">Mô tả tổng quan</div>
                    <div style="font-size:13px;color:#cbd5f5;line-height:1.6;">
                        <?= nl2br(htmlspecialchars($moTaNgan, ENT_QUOTES)) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div> <!-- /.pdp-right -->

    </div> <!-- /.pdp-page -->

    <!-- KHỐI MÔ TẢ CHI TIẾT PHÍA DƯỚI -->
    <section class="pdp-detail-section">
        <div class="pdp-detail-title">Mô tả chi tiết</div>
        <div class="pdp-detail-content">
            <?php if ($chiTiet): ?>
                <?= nl2br($chiTiet) ?>
            <?php else: ?>
                Chưa có mô tả chi tiết cho sản phẩm này.
            <?php endif; ?>
        </div>
    </section>

</main>

<?php @include BASE_PATH . '/includes/User/footer.php'; ?>
</body>
</html>
