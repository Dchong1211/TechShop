<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

function redirect_self(): void {
  $url = strtok($_SERVER['REQUEST_URI'], '?');
  header("Location: $url");
  exit;
}
function add_item(array $data): void {
  $id    = trim((string)($data['id']   ?? ''));
  $name  = trim((string)($data['name'] ?? ''));
  $price = (float)($data['price'] ?? 0);
  $img   = trim((string)($data['img']  ?? ''));
  $qty   = (int)($data['qty']   ?? 1);
  if ($id === '' || $name === '' || $price <= 0) return;
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] === $id) { $item['qty'] += max(1, $qty); return; }
  }
  $_SESSION['cart'][] = ['id'=>$id,'name'=>$name,'price'=>$price,'img'=>$img,'qty'=>max(1,$qty)];
}
function update_qty(array $quantities): void {
  foreach ($_SESSION['cart'] as $i => $item) {
    $id = $item['id'];
    if (isset($quantities[$id])) {
      $q = (int)$quantities[$id];
      if ($q <= 0) unset($_SESSION['cart'][$i]);
      else $_SESSION['cart'][$i]['qty'] = $q;
    }
  }
  $_SESSION['cart'] = array_values($_SESSION['cart']);
}
function remove_item(string $id): void {
  foreach ($_SESSION['cart'] as $i => $item) {
    if ($item['id'] === $id) { unset($_SESSION['cart'][$i]); $_SESSION['cart']=array_values($_SESSION['cart']); return; }
  }
}
function clear_cart(): void { $_SESSION['cart'] = []; }
function cart_subtotal(): float {
  $s = 0; foreach ($_SESSION['cart'] as $it) $s += (float)$it['price'] * (int)$it['qty']; return $s;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($action === 'add') { add_item($_POST); redirect_self(); }
  if ($action === 'update') { $q = $_POST['quantities'] ?? []; if (is_array($q)) update_qty($q); redirect_self(); }
} else {
  if ($action === 'remove') { $id = (string)($_GET['id'] ?? ''); if ($id!=='') remove_item($id); redirect_self(); }
  if ($action === 'clear') { clear_cart(); redirect_self(); }
}

$cart = $_SESSION['cart'];
$subtotal = cart_subtotal();
$shipping = $subtotal > 0 ? 0 : 0; // Logic phí ship có thể sửa sau
$discount = 0;
$total    = max(0, $subtotal + $shipping - $discount);

$BACK_URL = 'public/user/index.php';
$PAGE_TITLE = 'Giỏ hàng';

// Bắt đầu buffer để chèn CSS vào Header
ob_start();
?>
  <link rel="stylesheet" href="public/assets/css/cssUser/cart.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/header.php';
?>

  <div class="cart-progress-bar">
    <div class="progress-step active">
      <i>1</i> <span>Giỏ hàng</span>
    </div>
    <div class="progress-line"></div>
    <div class="progress-step">
      <i>2</i> <span>Thông tin đặt hàng</span>
    </div>
    <div class="progress-line"></div>
    <div class="progress-step">
      <i>3</i> <span>Thanh toán</span>
    </div>
    <div class="progress-line"></div>
    <div class="progress-step">
      <i>4</i> <span>Hoàn tất</span>
    </div>
  </div>

  <main class="cart-page">
    
    <?php if (empty($cart)): ?>
      <div class="empty-cart-box">
        <div class="empty-cart-icon"><i class="fa-solid fa-cart-shopping"></i></div>
        <p style="font-size:18px; color:#666;">Giỏ hàng của bạn đang trống</p>
        <a class="btn-checkout" href="<?= $BACK_URL ?>" style="max-width:200px; margin:20px auto; background:#1677ff;">Tiếp tục mua sắm</a>
      </div>

    <?php else: ?>
      <section class="cart-left-section">
        <div class="cart-header-actions">
          <a class="btn-text" href="<?= $BACK_URL ?>"><i class="fa-solid fa-chevron-left"></i> Mua thêm sản phẩm khác</a>
          <a class="btn-text" href="public/user/cart.php?action=clear" onclick="return confirm('Xoá toàn bộ giỏ hàng?')" style="color:#666;">
            <i class="fa-solid fa-trash-can"></i> Xoá tất cả
          </a>
        </div>

        <form method="post">
          <input type="hidden" name="action" value="update">
          <table class="cart-table">
            <thead>
              <tr>
                <th style="width: 45%">Sản phẩm</th>
                <th style="width: 15%">Đơn giá</th>
                <th style="width: 15%">Số lượng</th>
                <th style="width: 20%; text-align:right;">Thành tiền</th>
                <th style="width: 5%"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart as $item):
                $line = (float)$item['price'] * (int)$item['qty']; ?>
                <tr>
                  <td>
                    <div class="cart-item">
                      <?php if (!empty($item['img'])): ?>
                        <img src="<?= htmlspecialchars($item['img'], ENT_QUOTES) ?>" alt="Product Image">
                      <?php else: ?>
                        <img src="public/assets/images/no-image.png" alt="No Image">
                      <?php endif; ?>
                      <div class="item-info">
                        <h4><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></h4>
                        <span class="item-id">Mã SP: <?= htmlspecialchars($item['id'], ENT_QUOTES) ?></span>
                        <br>
                        <a class="item-remove" href="public/user/cart.php?action=remove&id=<?= urlencode($item['id']) ?>" onclick="return confirm('Xoá sản phẩm này?')">
                           Xoá
                        </a>
                      </div>
                    </div>
                  </td>
                  <td class="price-col"><?= number_format((float)$item['price'], 0, ',', '.') ?>₫</td>
                  <td>
                    <div class="qty-wrapper">
                      <input class="qty-input" type="number" name="quantities[<?= htmlspecialchars($item['id'], ENT_QUOTES) ?>]" value="<?= (int)$item['qty'] ?>" min="1">
                    </div>
                  </td>
                  <td class="subtotal-col" style="text-align:right;">
                    <?= number_format($line, 0, ',', '.') ?>₫
                  </td>
                  <td></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <div class="cart-footer-actions">
            <button class="btn-update" type="submit">Cập nhật giỏ hàng</button>
          </div>
        </form>
      </section>

      <aside class="summary-box">
        <h3>Tóm tắt đơn hàng</h3>
        <div class="summary-row">
          <span>Tạm tính</span>
          <strong><?= number_format($subtotal, 0, ',', '.') ?>₫</strong>
        </div>
        <div class="summary-row">
          <span>Phí vận chuyển</span>
          <span><?= $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . '₫' ?></span>
        </div>
        <div class="summary-row">
          <span>Giảm giá</span>
          <span><?= $discount === 0 ? '0₫' : ('- ' . number_format($discount, 0, ',', '.') . '₫') ?></span>
        </div>
        
        <div class="summary-row total">
          <span>Tổng cộng</span>
          <span><?= number_format($total, 0, ',', '.') ?>₫</span>
        </div>
        
        <div style="margin-top: 10px; font-style: italic; font-size: 13px; color: #666;">
          (Đã bao gồm VAT nếu có)
        </div>

        <a class="btn-checkout" href="public/user/checkout.php">Thanh toán</a>
        <a class="btn-continue" href="<?= $BACK_URL ?>">Tiếp tục mua hàng</a>
      </aside>
    <?php endif; ?>

  </main>

<?php
include BASE_PATH . '/includes/footer.php';
?>