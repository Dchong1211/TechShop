<?php
declare(strict_types=1);
session_start();

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
$shipping = $subtotal > 0 ? 0 : 0;
$discount = 0;
$total    = max(0, $subtotal + $shipping - $discount);

$BACK_URL = 'public/user/index.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Giỏ hàng | Techshop</title>
  <base href="/TechShop/">
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=7">
  <style>
    .cart-page{max-width:1200px;margin:24px auto;padding:0 16px;display:grid;grid-template-columns:1fr 320px;gap:24px}
    .cart-table{width:100%;border-collapse:collapse;background:#fff;border:1px solid #eee}
    .cart-table th,.cart-table td{padding:12px;border-bottom:1px solid #f0f0f0;vertical-align:middle}
    .cart-table th{background:#fafafa;text-align:left;font-weight:600}
    .cart-item{display:flex;gap:12px;align-items:center}
    .cart-item img{width:64px;height:64px;object-fit:cover;border-radius:8px}
    .qty-input{width:72px;padding:6px;text-align:center}
    .cart-actions a{color:#ff4d4f;text-decoration:none}
    .summary{background:#fff;border:1px solid #eee;border-radius:8px;padding:16px}
    .summary h3{margin:0 0 12px 0}
    .summary .row{display:flex;justify-content:space-between;margin:6px 0}
    .summary .total{font-weight:700;font-size:18px;border-top:1px dashed #ddd;padding-top:10px;margin-top:8px}
    .btn{display:inline-block;background:#1677ff;color:#fff;text-decoration:none;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
    .btn.secondary{background:#f0f0f0;color:#333}
    .cart-header-actions{display:flex;gap:12px;margin-bottom:10px}
    .empty-box{background:#fff;border:1px dashed #ddd;padding:24px;text-align:center;border-radius:8px}
    .price{white-space:nowrap}
  </style>
</head>
<body>
  <header class="main-header">
    <div class="header-inner">
      <a href="public/user/index.php" class="logo">Techshop</a>
      <div class="header-actions">
        <a href="tel:19001234">📞 Hotline</a>
        <a href="public/user/orders.php">📦 Đơn hàng</a>
        <a href="public/user/cart.php">🛒 Giỏ hàng</a>
      </div>
    </div>
    <nav class="main-nav">
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

  <main class="cart-page">
    <section>
      <div class="cart-header-actions">
        <a class="btn secondary" href="<?= $BACK_URL ?>">← Tiếp tục mua sắm</a>
        <a class="btn secondary" href="public/user/cart.php?action=clear" onclick="return confirm('Xoá toàn bộ giỏ hàng?')">🗑 Xoá giỏ</a>
      </div>

      <?php if (empty($cart)): ?>
        <div class="empty-box">
          <p>Giỏ hàng của bạn đang trống.</p>
          <a class="btn" href="<?= $BACK_URL ?>">Mua gì đó ngay</a>
        </div>
      <?php else: ?>
        <form method="post">
          <input type="hidden" name="action" value="update">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th style="width:120px">Số lượng</th>
                <th>Tạm tính</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart as $item):
                $line = (float)$item['price'] * (int)$item['qty']; ?>
                <tr>
                  <td>
                    <div class="cart-item">
                      <?php if (!empty($item['img'])): ?>
                        <img src="<?= htmlspecialchars($item['img'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>">
                      <?php else: ?>
                        <img src="https://via.placeholder.com/64x64?text=No+Img" alt="No image">
                      <?php endif; ?>
                      <div>
                        <div style="font-weight:600"><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></div>
                        <small>#<?= htmlspecialchars($item['id'], ENT_QUOTES) ?></small>
                      </div>
                    </div>
                  </td>
                  <td class="price"><?= number_format((float)$item['price'], 0, ',', '.') ?> ₫</td>
                  <td>
                    <input class="qty-input" type="number" name="quantities[<?= htmlspecialchars($item['id'], ENT_QUOTES) ?>]" value="<?= (int)$item['qty'] ?>" min="0">
                  </td>
                  <td class="price"><?= number_format($line, 0, ',', '.') ?> ₫</td>
                  <td class="cart-actions">
                    <a href="public/user/cart.php?action=remove&id=<?= urlencode($item['id']) ?>" onclick="return confirm('Xoá sản phẩm này?')">Xoá</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <div style="margin-top:12px; display:flex; gap:12px;">
            <button class="btn" type="submit">🔄 Cập nhật giỏ</button>
            <a class="btn secondary" href="<?= $BACK_URL ?>">+ Thêm sản phẩm</a>
          </div>
        </form>
      <?php endif; ?>
    </section>

    <aside class="summary">
      <h3>Tóm tắt đơn hàng</h3>
      <div class="row"><span>Tạm tính</span><strong class="price"><?= number_format($subtotal, 0, ',', '.') ?> ₫</strong></div>
      <div class="row"><span>Vận chuyển</span><span><?= $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . ' ₫' ?></span></div>
      <div class="row"><span>Giảm giá</span><span><?= $discount === 0 ? '0 ₫' : ('- ' . number_format($discount, 0, ',', '.') . ' ₫') ?></span></div>
      <div class="row total"><span>Tổng cộng</span><span class="price"><?= number_format($total, 0, ',', '.') ?> ₫</span></div>
      <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
        <a class="btn" href="public/user/checkout.php">✅ Thanh toán</a>
        <a class="btn secondary" href="<?= $BACK_URL ?>">← Tiếp tục mua</a>
      </div>
    </aside>
  </main>

  <footer>© <?= date('Y') ?> Techshop</footer>
</body>
</html>
