<?php
define('BASE_PATH', dirname(__DIR__));
session_start();

$product_id = $_GET['id'] ?? '';
$product = null;

if ($product_id) {
    $json_data = @file_get_contents("http://localhost/TechShop/app/api/get_products.php");
    $all_products = ($json_data) ? json_decode($json_data, true) : [];
    
    if (is_array($all_products)) {
        foreach ($all_products as $p) {
            if (isset($p['id']) && (string)$p['id'] === $product_id) {
                $product = $p;
                break;
            }
        }
    }
}

if (!$product) {
    $PAGE_TITLE = 'Không tìm thấy';
    $SHOW_SEARCH = true;
    include BASE_PATH . '/includes/header.php';
    echo '<main class="homepage" role="main" style="padding: 20px;"><div class="main-content"><p>Sản phẩm không tồn tại hoặc đã bị xoá.</p><a href="public/user/index.php">Về trang chủ</a></div></main>';
    include BASE_PATH . '/includes/footer.php';
    exit;
}

$PAGE_TITLE = $product['name'];
$SHOW_SEARCH = true;

$ADDITIONAL_HEAD_CONTENT = '<link rel="stylesheet" href="public/assets/css/cssUser/product_detail.css?v=1">';

include BASE_PATH . '/includes/header.php';
?>

  <main class="homepage" role="main">
    <div class="main-content">
      <div class="pdp-page">
        <div class="pdp-image">
          <img src="<?= htmlspecialchars($product['image'] ?? 'https://via.placeholder.com/500x500') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        
        <div class="pdp-info">
          <h1><?= htmlspecialchars($product['name']) ?></h1>
          <div class="pdp-price"><?= number_format((float)$product['price'], 0, ',', '.') ?>đ</div>
          
          <p class="pdp-desc">
            (Đây là mô tả sản phẩm. Bạn có thể thêm trường 'description' vào API và hiển thị ở đây.)
          </p>

          <form class="pdp-form" action="public/user/cart.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
            <input type="hidden" name="img" value="<?= htmlspecialchars($product['image'] ?? '') ?>">
            
            <div>
              <label for="qty">Số lượng:</label>
              <input type="number" id="qty" name="qty" value="1" min="1">
              <button type="submit" class="btn">Thêm vào giỏ hàng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

<?php
include BASE_PATH . '/includes/footer.php';
?>