<?php
declare(strict_types=1);
session_start();
$orders = $_SESSION['orders'] ?? [];
function money_vn($v){ return number_format((float)$v,0,',','.').' ₫'; }
$viewId = $_GET['id'] ?? '';
$view = null;
if ($viewId) {
  foreach ($orders as $o) if ($o['id']===$viewId) {$view=$o;break;}
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đơn hàng | Techshop</title>
  <base href="/TechShop/">
  <link rel="stylesheet" href="public/assets/css/cssUser/user.css?v=7">
  <style>
    .page{max-width:1200px;margin:24px auto;padding:0 16px;display:grid;grid-template-columns:1fr 360px;gap:24px}
    .card{background:#fff;border:1px solid #eee;border-radius:10px;padding:16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #f2f2f2;vertical-align:middle}
    th{background:#fafafa;text-align:left}
    .btn{display:inline-block;background:#1677ff;color:#fff;text-decoration:none;border:none;padding:10px 14px;border-radius:10px}
    .btn.secondary{background:#f0f0f0;color:#333}
    .empty{background:#fff;border:1px dashed #ddd;padding:24px;border-radius:10px;text-align:center}
    .row{display:flex;justify-content:space-between;margin:6px 0}
    .total{font-weight:700}
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

  <?php if ($view): ?>
    <main class="page">
      <section class="card">
        <h2>Chi tiết đơn <?= htmlspecialchars($view['id'],ENT_QUOTES) ?></h2>
        <div class="row"><span>Người nhận</span><strong><?= htmlspecialchars($view['fullname'],ENT_QUOTES) ?></strong></div>
        <div class="row"><span>Điện thoại</span><span><?= htmlspecialchars($view['phone'],ENT_QUOTES) ?></span></div>
        <div class="row"><span>Địa chỉ</span><span><?= htmlspecialchars($view['address'],ENT_QUOTES) ?></span></div>
        <div class="row"><span>Thanh toán</span><span><?= $view['payment']==='cod'?'COD':'Chuyển khoản' ?></span></div>
        <div class="row"><span>Thời gian</span><span><?= htmlspecialchars($view['created_at'],ENT_QUOTES) ?></span></div>
        <?php if(!empty($view['note'])): ?><div class="row"><span>Ghi chú</span><span><?= htmlspecialchars($view['note'],ENT_QUOTES) ?></span></div><?php endif; ?>
        <table style="margin-top:12px">
          <thead><tr><th>Sản phẩm</th><th style="width:90px">SL</th><th style="width:140px">Thành tiền</th></tr></thead>
          <tbody>
          <?php foreach ($view['items'] as $it):
            $line = (float)$it['price']*(int)$it['qty']; ?>
            <tr>
              <td><div style="font-weight:600"><?= htmlspecialchars($it['name'],ENT_QUOTES) ?></div><small><?= money_vn($it['price']) ?></small></td>
              <td><?= (int)$it['qty'] ?></td>
              <td><?= money_vn($line) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </section>
      <aside class="card">
        <h3>Tổng kết</h3>
        <div class="row"><span>Tạm tính</span><span><?= money_vn($view['subtotal']) ?></span></div>
        <div class="row"><span>Vận chuyển</span><span><?= $view['shipping']===0?'Miễn phí':money_vn($view['shipping']) ?></span></div>
        <div class="row"><span>Giảm giá</span><span><?= money_vn($view['discount']) ?></span></div>
        <div class="row total"><span>Tổng cộng</span><span><?= money_vn($view['total']) ?></span></div>
        <div style="margin-top:12px;display:flex;gap:10px">
          <a class="btn secondary" href="public/user/orders.php">← Danh sách đơn</a>
          <a class="btn" href="public/user/index.php">Tiếp tục mua</a>
        </div>
      </aside>
    </main>
  <?php else: ?>
    <main class="page">
      <section class="card" style="grid-column:1/-1">
        <h2>Đơn hàng của tôi</h2>
        <?php if (empty($orders)): ?>
          <div class="empty">
            <p>Bạn chưa có đơn hàng nào.</p>
            <a class="btn" href="public/user/index.php">Mua gì đó ngay</a>
          </div>
        <?php else: ?>
          <table>
            <thead><tr><th>Mã đơn</th><th>Ngày</th><th>Người nhận</th><th>Tổng cộng</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td><?= htmlspecialchars($o['id'],ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($o['created_at'],ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($o['fullname'],ENT_QUOTES) ?></td>
                <td><?= money_vn($o['total']) ?></td>
                <td><a class="btn" href="public/user/orders.php?id=<?= urlencode($o['id']) ?>">Xem</a></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </section>
    </main>
  <?php endif; ?>

  <footer>© <?= date('Y') ?> Techshop</footer>
</body>
</html>
