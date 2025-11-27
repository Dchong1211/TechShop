<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = $_SESSION['cart'];
if (empty($cart)) {
  header('Location: public/user/cart.php');
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
      'status'=>'pending',
      'created_at'=>date('Y-m-d H:i:s')
    ];
    $_SESSION['cart'] = [];
    $done = true;
  }
}

$PAGE_TITLE = 'Thanh toán';

ob_start();
?>
  <link rel="stylesheet" href="public/assets/css/cssUser/checkout.css?v=1">
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/User/header.php';
?>

  <?php if ($done): ?>
    <main class="success">
      <div class="card" style="text-align:center">
        <div style="font-size: 50px; color: #52c41a;"><i class="fa-regular fa-circle-check"></i></div>
        <div class="ok">Đặt hàng thành công</div>
        <div style="color: #aaa;">Mã đơn: <strong style="color: #AD90E8;"><?= htmlspecialchars($orderId,ENT_QUOTES) ?></strong></div>
        <p style="color: #aaa;">Cảm ơn bạn đã mua hàng tại TechShop.</p>
        <div style="display:flex;gap:12px;justify-content:center;margin-top:20px">
          <a class="btn" href="public/user/orders.php" style="background:#1677ff;">Xem đơn hàng</a>
          <a class="btn secondary" href="public/user/index.php">Về trang chủ</a>
        </div>
      </div>
    </main>
  <?php else: ?>
    <main class="page">
      <section class="card">
        <h2 style="margin-top:0;">Thông tin nhận hàng</h2>
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
          <div style="display:flex;gap:12px;align-items:center;margin-top:20px">
            <button class="btn" type="submit" style="width:150px">Đặt hàng</button>
            <a class="btn secondary" href="public/user/cart.php">← Giỏ hàng</a>
          </div>
        </form>
      </section>

      <aside class="card">
        <h2 style="margin-top:0;">Tóm tắt đơn hàng</h2>
        <table class="items">
          <thead><tr><th>Sản phẩm</th><th style="width:50px">SL</th><th style="width:110px;text-align:right">Tiền</th></tr></thead>
          <tbody>
          <?php foreach ($cart as $it):
            $line = (float)$it['price'] * (int)$it['qty']; ?>
            <tr>
              <td>
                <div style="font-weight:600;font-size:13px"><?= htmlspecialchars($it['name'],ENT_QUOTES) ?></div>
                <small style="color:#888"><?= money_vn($it['price']) ?></small>
              </td>
              <td style="text-align:center"><?= (int)$it['qty'] ?></td>
              <td style="text-align:right"><?= money_vn($line) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <div class="row" style="margin-top: 15px;"><span>Tạm tính</span><strong><?= money_vn($subtotal) ?></strong></div>
        <div class="row"><span>Vận chuyển</span><span><?= $shipping===0?'Miễn phí':money_vn($shipping) ?></span></div>
        <div class="row total"><span>Tổng cộng</span><span style="color:#d70018"><?= money_vn($total) ?></span></div>
      </aside>
    </main>
  <?php endif; ?>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>