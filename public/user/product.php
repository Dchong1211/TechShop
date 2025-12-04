<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('PUBLIC_PATH', dirname(__DIR__)); // C:\xampp\htdocs\TechShop\public

// ================== KẾT NỐI DATABASE ==================
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'techshop';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Kết nối database thất bại: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// ================== INPUT TỪ URL ==================
$cate = isset($_GET['cate']) ? trim($_GET['cate']) : '';   // co the la id_dm hoac 'search'
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$q    = isset($_GET['q'])    ? trim($_GET['q'])    : '';   // tu khoa tim kiem

// ================== WHERE LỌC SẢN PHẨM ==================
$where          = " WHERE sp.trang_thai = 1 ";
$categoryTitle  = 'Tất cả sản phẩm';

// --- TRƯỜNG HỢP TÌM KIẾM ---
if ($cate === 'search') {
    if ($q !== '') {
        $safeQ = $conn->real_escape_string($q);
        // tim trong ten_sp + mo_ta_ngan
        $where .= " AND (sp.ten_sp LIKE '%{$safeQ}%' OR sp.mo_ta_ngan LIKE '%{$safeQ}%') ";
        $categoryTitle = "Kết quả tìm kiếm cho: " . $q;
    } else {
        $categoryTitle = "Tìm kiếm sản phẩm";
    }
}
// --- LỌC THEO DANH MỤC BÌNH THƯỜNG ---
elseif ($cate !== '') {
    if (ctype_digit($cate)) {
        $idDm = (int)$cate;
        $where .= " AND sp.id_dm = {$idDm} ";

        $sqlDm = "SELECT ten_dm FROM danh_muc WHERE id = {$idDm}";
        if ($rsDm = $conn->query($sqlDm)) {
            if ($rsDm->num_rows > 0) {
                $rowDm = $rsDm->fetch_assoc();
                $categoryTitle = $rowDm['ten_dm'];
            }
        }
    } else {
        $categoryTitle = ucfirst($cate);
    }
}

// ================== SẮP XẾP ==================
switch ($sort) {
    case 'price_asc':
        $orderBy = " ORDER BY COALESCE(sp.gia_khuyen_mai, sp.gia) ASC ";
        break;
    case 'price_desc':
        $orderBy = " ORDER BY COALESCE(sp.gia_khuyen_mai, sp.gia) DESC ";
        break;
    case 'name_asc':
        $orderBy = " ORDER BY sp.ten_sp ASC ";
        break;
    case 'name_desc':
        $orderBy = " ORDER BY sp.ten_sp DESC ";
        break;
    default:
        $orderBy = " ORDER BY sp.id DESC ";
}

// ================== PHÂN TRANG ==================
$perPage = 12;
$offset  = ($page - 1) * $perPage;

$sqlProducts = "
    SELECT SQL_CALC_FOUND_ROWS
        sp.id,
        sp.ten_sp,
        sp.gia,
        sp.gia_khuyen_mai,
        sp.hinh_anh,
        sp.mo_ta_ngan
    FROM san_pham sp
    {$where}
    {$orderBy}
    LIMIT {$offset}, {$perPage}
";
$rsProducts = $conn->query($sqlProducts);

$rsTotal    = $conn->query("SELECT FOUND_ROWS() AS total");
$rowTotal   = $rsTotal ? $rsTotal->fetch_assoc() : ['total' => 0];
$totalRows  = (int)$rowTotal['total'];
$totalPages = max(1, (int)ceil($totalRows / $perPage));

// Build base URL GIỮ cate + sort + q (để phân trang)
$baseQuery = [];
if ($cate !== '') $baseQuery['cate'] = $cate;
if ($q    !== '') $baseQuery['q']    = $q;
if ($sort !== '') $baseQuery['sort'] = $sort;

// DÙNG ROUTE /products THAY VÌ GỌI THẲNG FILE
$baseUrl = 'public/products';
if (!empty($baseQuery)) {
    $baseUrl .= '?' . http_build_query($baseQuery);
}

function pageLink($baseUrl, $pageNum) {
    $join = (strpos($baseUrl, '?') === false) ? '?' : '&';
    return $baseUrl . $join . 'page=' . $pageNum;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>TechShop | <?= htmlspecialchars($categoryTitle) ?></title>
    <base href="/TechShop/">
    <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=1">
    <link rel="stylesheet" href="public/assets/css/cssUser/product.css?v=2">
</head>
<body>
<?php @include PUBLIC_PATH . '/includes/User/header.php'; ?>

<main class="main-content">

    <div class="product-page-container">

        <!-- SIDEBAR DANH MỤC -->
        <aside class="col-left-sidebar">
            <?php @include PUBLIC_PATH . '/includes/User/sidebar.php'; ?>
        </aside>

        <!-- CỘT SẢN PHẨM CHÍNH -->
        <div class="col-main-content">

            <!-- HEADER TOP (TITLE + SORT) -->
            <section class="product-header-block">
                <div class="product-header-block-left">
                    <h1><?= htmlspecialchars($categoryTitle) ?></h1>
                    <span class="sub-text">
                        <?php
                        if ($totalRows > 0) {
                            if ($cate === 'search' && $q !== '') {
                                echo "Tìm thấy {$totalRows} sản phẩm cho \"".htmlspecialchars($q)."\"";
                            } else {
                                echo "Tìm thấy {$totalRows} sản phẩm";
                            }
                        } else {
                            echo "Không tìm thấy sản phẩm nào";
                        }
                        ?>
                    </span>
                </div>

                <div class="product-header-block-right">
                    <!-- SORT CŨNG ĐI QUA ROUTE /products -->
                    <form method="get" action="public/product" class="product-sort">
                        <?php if ($cate !== ''): ?>
                            <input type="hidden" name="cate" value="<?= htmlspecialchars($cate) ?>">
                        <?php endif; ?>
                        <?php if ($q !== ''): ?>
                            <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">
                        <?php endif; ?>

                        <label for="sort">Sắp xếp</label>
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="">Mặc định</option>
                            <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected' : '' ?>>Giá tăng dần</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                            <option value="name_asc"   <?= $sort === 'name_asc'   ? 'selected' : '' ?>>Tên A → Z</option>
                            <option value="name_desc"  <?= $sort === 'name_desc'  ? 'selected' : '' ?>>Tên Z → A</option>
                        </select>
                    </form>
                </div>
            </section>

            <!-- LIST SẢN PHẨM -->
            <section class="product-list-wrapper">
                <div class="product-grid-page">
                    <?php if ($totalRows === 0): ?>
                        <div class="empty-result">
                            Không có sản phẩm nào phù hợp với bộ lọc.
                        </div>
                    <?php else: ?>
                        <?php while ($p = $rsProducts->fetch_assoc()): ?>
                            <?php
                            $id   = (int)$p['id'];
                            $name = $p['ten_sp'];
                            $gia  = (float)$p['gia'];
                            $sale = $p['gia_khuyen_mai'] !== null ? (float)$p['gia_khuyen_mai'] : null;

                            $displayPrice = ($sale !== null && $sale > 0 && $sale < $gia) ? $sale : $gia;
                            $oldPrice     = ($sale !== null && $sale > 0 && $sale < $gia) ? $gia : 0;

                            $discountPercent = 0;
                            if ($oldPrice > 0) {
                                $discountPercent = round(100 - $displayPrice * 100 / $oldPrice);
                            }

                            // xử lý ảnh sản phẩm
                            $rawThumb = trim((string)$p['hinh_anh']);

                            if ($rawThumb === '' || $rawThumb === null) {
                                $thumb = 'public/assets/images/TechShop.jpg';
                            } elseif (preg_match('~^https?://~i', $rawThumb)) {
                                $thumb = $rawThumb;
                            } else {
                                // tuỳ cấu trúc project, tạm để trong assets/images
                                $thumb = 'public/assets/images/' . $rawThumb;
                            }
                            ?>
                            <div class="product-card">
                                <?php if ($discountPercent > 0): ?>
                                    <div class="product-label">-<?= $discountPercent ?>%</div>
                                <?php endif; ?>

                                <!-- ẢNH + TÊN: click vào sẽ tới chi tiết (qua route /products/{id}) -->
                                <a href="public/products/<?= $id ?>">
                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($name) ?>">
                                    <h3><?= htmlspecialchars($name) ?></h3>
                                </a>

                                <div class="price-block">
                                    <div class="price">
                                        <?= number_format($displayPrice, 0, ',', '.') ?> ₫
                                    </div>
                                    <?php if ($oldPrice > 0): ?>
                                        <div class="old-price-line">
                                            <span class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?> ₫</span>
                                            <span class="discount-tag">Giảm <?= $discountPercent ?>%</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-actions">
                                    <!-- THÊM GIỎ HÀNG: POST tới route /cart -->
                                    <form method="post" action="public/cart">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="id_san_pham" value="<?= $id ?>">
                                        <input type="hidden" name="so_luong" value="1">
                                        <button type="submit" class="add-cart-btn">
                                            Thêm vào giỏ
                                        </button>
                                    </form>

                                    <!-- XEM CHI TIẾT: cũng dùng route /products/{id} -->
                                    <a href="public/products/<?= $id ?>" class="add-cart-btn">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <!-- PHÂN TRANG -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a class="page-link" href="<?= htmlspecialchars(pageLink($baseUrl, $page-1)) ?>">&laquo;</a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($totalPages, $page + 2);

                        if ($start > 1) {
                            echo '<a class="page-link" href="'.htmlspecialchars(pageLink($baseUrl, 1)).'">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-link" style="background:transparent;border:none;">…</span>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            $cls = 'page-link';
                            if ($i == $page) $cls .= ' active';
                            echo '<a class="'.$cls.'" href="'.htmlspecialchars(pageLink($baseUrl, $i)).'">'.$i.'</a>';
                        }

                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) {
                                echo '<span class="page-link" style="background:transparent;border:none;">…</span>';
                            }
                            echo '<a class="page-link" href="'.htmlspecialchars(pageLink($baseUrl, $totalPages)).'">'.$totalPages.'</a>';
                        }

                        if ($page < $totalPages) {
                            echo '<a class="page-link" href="'.htmlspecialchars(pageLink($baseUrl, $page+1)).'">&raquo;</a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </section>

        </div><!-- /.col-main-content -->
    </div><!-- /.product-page-container -->

</main>

<?php @include PUBLIC_PATH . '/includes/User/footer.php'; ?>
</body>
</html>
