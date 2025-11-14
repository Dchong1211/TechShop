<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = $_SESSION['cart'];
if (empty($cart)) {
  header('Location: /TechShop/public/user/cart.php');
  exit;
}
function money_vn($v){ return number_format((float)$v,0,',','.').' ₫'; }
$subtotal = 0;
foreach ($cart as $it) $subtotal += (float)$it['price'] * (int)$it['qty'];
$shipping = 0;
$discount = 0;
$total = max(0, $subtotal + $shipping - $discount);
$errors = [];
$done = false;
$orderId = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $fullname = trim($_POST['fullname'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $payment = trim($_POST['payment'] ?? 'cod');
  $note = trim($_POST['note'] ?? '');
  if ($fullname==='') $errors['fullname']='Vui lòng nhập họ tên';
  if ($phone==='' || !preg_match('/^[0-9+\-\s]{8,15}$/',$phone)) $errors['phone']='Số điện thoại không hợp lệ';
  if ($address==='') $errors['address']='Vui lòng nhập địa chỉ';
  if (!$errors) {
    if (!isset($_SESSION['orders'])) $_SESSION['orders'] = [];
    $orderId = strtoupper('OD'.substr(uniqid(),-8));
    $_SESSION['orders'][] = [
      'id'=>$orderId,
      'items'=>$cart,
      'subtotal'=>$subtotal,
      'shipping'=>$shipping,
      'discount'=>$discount,
      'total'=>$total,
      'fullname'=>$fullname,
      'phone'=>$phone,
      'address'=>$address,
      'payment'=>$payment,
      'note'=>$note,
      'created_at'=>date('Y-m-d H:i:s')
    ];
    $_SESSION['cart'] = [];
    $done = true;
  }
}

$PAGE_TITLE = 'Thanh toán';

ob_start();
?>
  <style>
    .page{max-width:1200px;margin:24px auto;padding:0 16px;display:grid;grid-template-columns:1fr 380px;gap:24px}
    .card{background:#fff;border:1px solid #eee;border-radius:10px;padding:16px}
    .field{margin-bottom:12px}
    .field label{display:block;font-weight:600;margin-bottom:6px}
    .field input,.field textarea,.field select{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .err{color:#d63031;font-size:13px;margin-top:4px}
    .items{width:100%;border-collapse:collapse}
    .items th,.items td{padding:10px;border-bottom:1px solid #f3f3f3;vertical-align:middle}
    .items th{text-align:left;font-weight:600;background:#fafafa}
    .row{display:flex;justify-content:space-between;margin:6px 0}
    .total{font-weight:700;font-size:18px;border-top:1px dashed #ddd;padding-top:10px;margin-top:8px}
    .btn{display:inline-block;background:#1677ff;color:#fff;text-decoration:none;border:none;padding:12px 14px;border-radius:10px;cursor:pointer;text-align:center}
    .btn.secondary{background:#f0f0f0;color:#333}
    .success{max-width:720px;margin:40px auto}
    .ok{font-size:20px;font-weight:700;margin:8px 0}
  </style>
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/header.php';
?>

  <?php if ($done): ?>
    <main class="success">
      <div class="card" style="text-align:center">
        <div>Đặt hàng thành công</div>
        <div class="ok">Mã đơn: <?= htmlspecialchars($orderId,ENT_QUOTES) ?></div>
        <p>Chúng tôi đã nhận đơn hàng của bạn.</p>
        <div style="display:flex;gap:12px;justify-content:center;margin-top:12px">
          <a class="btn" href="public/user/orders.php">Xem đơn hàng</a>
          <a class="btn secondary" href="public/user/index.php">Về trang chủ</a>
        </div>
      </div>
    </main>
  <?php else: ?>
    <main class="page">
      <section class="card">
        <h2>Thông tin nhận hàng</h2>
        <form method="post" novalidate>
          <div class="field">
            <label>Họ và tên</label>
            <input name="fullname" value="<?= htmlspecialchars($_POST['fullname'] ?? '',ENT_QUOTES) ?>">
            <?php if(isset($errors['fullname'])): ?><div class="err"><?= $errors['fullname'] ?></div><?php endif; ?>
          </div>
          <div class="field">
            <label>Số điện thoại</label>
            <input name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '',ENT_QUOTES) ?>">
            <?php if(isset($errors['phone'])): ?><div class="err"><?= $errors['phone'] ?></div><?php endif; ?>
          </div>
          <div class="field">
            <label>Địa chỉ</label>
            <input name="address" value="<?= htmlspecialchars($_POST['address'] ?? '',ENT_QUOTES) ?>">
            <?php if(isset($errors['address'])): ?><div class="err"><?= $errors['address'] ?></div><?php endif; ?>
          </div>
          <div class="field">
            <label>Phương thức thanh toán</label>
            <select name="payment">
              <option value="cod" <?= (($_POST['payment'] ?? 'cod')==='cod')?'selected':''; ?>>Thanh toán khi nhận hàng (COD)</option>
              <option value="bank" <?= (($_POST['payment'] ?? '')==='bank')?'selected':''; ?>>Chuyển khoản</option>
            </select>
          </div>
          <div class="field">
            <label>Ghi chú</label>
            <textarea name="note" rows="3"><?= htmlspecialchars($_POST['note'] ?? '',ENT_QUOTES) ?></textarea>
          </div>
          <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
            <button class="btn" type="submit">Đặt hàng</button>
            <a class="btn secondary" href="public/user/cart.php">← Quay lại giỏ hàng</a>
          </div>
        </form>
      </section>

      <aside class="card">
        <h2>Tóm tắt đơn hàng</h2>
        <table class="items">
          <thead><tr><th>Sản phẩm</th><th style="width:90px">SL</th><th style="width:140px">Thành tiền</th></tr></thead>
          <tbody>
          <?php foreach ($cart as $it):
            $line = (float)$it['price'] * (int)$it['qty']; ?>
            <tr>
              <td>
                <div style="font-weight:600"><?= htmlspecialchars($it['name'],ENT_QUOTES) ?></div>
                <small><?= money_vn($it['price']) ?></small>
              </td>
              <td><?= (int)$it['qty'] ?></td>
              <td><?= money_vn($line) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <div class="row"><span>Tạm tính</span><strong><?= money_vn($subtotal) ?></strong></div>
        <div class="row"><span>Vận chuyển</span><span><?= $shipping===0?'Miễn phí':money_vn($shipping) ?></span></div>
        <div class="row"><span>Giảm giá</span><span><?= money_vn($discount) ?></span></div>
        <div class="row total"><span>Tổng cộng</span><span><?= money_vn($total) ?></span></div>
      </aside>
    </main>
  <?php endif; ?>

<?php
include BASE_PATH . '/includes/footer.php';
?>