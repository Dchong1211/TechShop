<?php
define('BASE_PATH', dirname(__DIR__));
session_start();

$category_slug = $_GET['cate'] ?? 'all';
$page_title = 'Sản phẩm';

$json_data = @file_get_contents("http://localhost/TechShop/app/api/get_products.php");
$all_products = ($json_data) ? json_decode($json_data, true) : [];
$products_list = [];

if (!is_array($all_products)) {
    $all_products = [];
}

if ($category_slug == 'all') {
    $products_list = $all_products;
    $page_title = 'Tất cả sản phẩm';
} else {
    // Lọc tương đối đơn giản theo chuỗi
    $products_list = array_filter($all_products, function($p) use ($category_slug) {
        if (empty($p['category'])) return false;
        $c = strtolower((string)$p['category']);
        return str_contains($c, strtolower($category_slug));
    });
    $page_title = 'Sản phẩm ' . htmlspecialchars(ucfirst($category_slug));
}

$SHOW_SEARCH = true;

include BASE_PATH . '/includes/User/header.php'; // ĐÃ SỬA
?>

  <main class="homepage" role="main">
    <div class="main-content">
      <div class="row">

        <div class="col-xl-3 col-lg-3 col-left-sidebar">
          <?php include BASE_PATH . '/includes/User/sidebar.php'; // ĐÃ SỬA ?>
        </div>

        <div class="col-xl-9 col-lg-9 col-main-content">
          <section class="product-section">
            <div class="section-head">
              <h2 id="product-list-title"><?= $page_title ?></h2>
            </div>
            
            <div class="products">
              <?php if (empty($products_list)): ?>
                <p style="grid-column: 1 / -1; padding: 20px;">Không tìm thấy sản phẩm nào trong danh mục này.</p>
              <?php else: ?>
                <?php foreach ($products_list as $item): ?>
                  <div class="product-card">
                    <img src="<?= htmlspecialchars($item['image'] ?? 'https://via.placeholder.com/240x140') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="price"><?= number_format((float)($item['price'] ?? 0), 0, ',', '.') ?>đ</p>
                    <a href="public/user/product_detail.php?id=<?= htmlspecialchars($item['id']) ?>">Xem chi tiết</a>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

          </section>
        </div>
      </div>
    </div>
  </main>

<?php
include BASE_PATH . '/includes/User/footer.php'; // ĐÃ SỬA
?>