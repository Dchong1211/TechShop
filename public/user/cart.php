<?php
declare(strict_types=1);
session_start();

define('BASE_PATH', dirname(__DIR__));

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

// ================== SESSION CART ==================
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/**
 * Chuyển hướng lại chính trang cart (xoá query string cho sạch)
 */
function redirect_self(): void {
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $url");
    exit;
}

/**
 * Lấy thông tin sản phẩm từ DB theo id
 */
function fetch_product(mysqli $conn, int $id): ?array {
    if ($id <= 0) return null;
    $stmt = $conn->prepare("
        SELECT id, ten_sp, gia, gia_khuyen_mai, hinh_anh
        FROM san_pham
        WHERE id = ? AND trang_thai = 1
        LIMIT 1
    ");
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
}

/**
 * Thêm sản phẩm vào giỏ theo id từ DB
 */
function add_item_from_db(mysqli $conn, int $id, int $qty = 1): void {
    if ($id <= 0 || $qty <= 0) return;

    $product = fetch_product($conn, $id);
    if (!$product) return;

    $price = (float)$product['gia'];
    if (isset($product['gia_khuyen_mai']) && (float)$product['gia_khuyen_mai'] > 0 && (float)$product['gia_khuyen_mai'] < $price) {
        $price = (float)$product['gia_khuyen_mai'];
    }

    // Path ảnh (tuỳ bạn đặt – sửa lại nếu bạn dùng thư mục khác)
    $img = $product['hinh_anh']
        ? 'public/assets/images/' . $product['hinh_anh']
        : 'public/assets/images/TechShop.jpg';

    $key = (string)$product['id'];

    if (!isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key] = [
            'id'    => $key,
            'name'  => $product['ten_sp'],
            'price' => $price,
            'img'   => $img,
            'qty'   => 0,
        ];
    }

    $_SESSION['cart'][$key]['qty'] += max(1, $qty);
}

/**
 * Cập nhật số lượng các item
 */
function update_qty(array $quantities): void {
    foreach ($quantities as $id => $q) {
        $id = (string)$id;
        $q  = (int)$q;
        if (!isset($_SESSION['cart'][$id])) continue;

        if ($q <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['qty'] = $q;
        }
    }
}

/**
 * Xoá 1 item
 */
function remove_item(string $id): void {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

/**
 * Xoá hết giỏ
 */
function clear_cart(): void {
    $_SESSION['cart'] = [];
}

/**
 * Tính tạm tính
 */
function cart_subtotal(): float {
    $s = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $s += (float)$item['price'] * (int)$item['qty'];
    }
    return $s;
}

// ================== XỬ LÝ ACTION ==================
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        // Nhận từ product.php / product_detail.php
        $id  = (int)($_POST['id_san_pham'] ?? 0);
        $qty = (int)($_POST['so_luong'] ?? 1);
        add_item_from_db($conn, $id, max(1, $qty));
        redirect_self();
    }

    if ($action === 'update') {
        $q = $_POST['quantities'] ?? [];
        if (is_array($q)) {
            update_qty($q);
        }
        redirect_self();
    }
} else {
    if ($action === 'remove') {
        $id = (string)($_GET['id'] ?? '');
        if ($id !== '') {
            remove_item($id);
        }
        redirect_self();
    }

    if ($action === 'clear') {
        clear_cart();
        redirect_self();
    }
}

// ================== TÍNH TOÁN TỔNG ==================
$cart     = $_SESSION['cart'];
$subtotal = cart_subtotal();
$shipping = 0;
$discount = 0;
$total    = max(0, $subtotal + $shipping - $discount);

// ================== BIẾN DÙNG CHO HEADER ==================
$BACK_URL   = 'public/user/index.php';
$PAGE_TITLE = 'Giỏ hàng';

// Chèn thêm CSS / fontawesome vào <head> (header.php sẽ echo biến này)
ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/cart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

// HEADER
include BASE_PATH . '/includes/User/header.php';
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
      <a class="btn-checkout" href="<?= $BACK_URL ?>" style="max-width:200px; margin:20px auto; background:#1677ff;">
        Tiếp tục mua sắm
      </a>
    </div>

  <?php else: ?>
    <section class="cart-left-section">
      <div class="cart-header-actions">
        <a class="btn-text" href="<?= $BACK_URL ?>">
          <i class="fa-solid fa-chevron-left"></i> Mua thêm sản phẩm khác
        </a>
        <a class="btn-text" href="public/user/cart.php?action=clear"
           onclick="return confirm('Xoá toàn bộ giỏ hàng?')" style="color:#666;">
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
              $line = (float)$item['price'] * (int)$item['qty'];
              $id   = (string)$item['id'];
              ?>
            <tr>
              <td>
                <div class="cart-item">
                  <?php if (!empty($item['img'])): ?>
                    <img src="<?= htmlspecialchars($item['img'], ENT_QUOTES) ?>" alt="Product Image">
                  <?php else: ?>
                    <img src="public/assets/images/no-image.png" alt="No Image">
                  <?php endif; ?>
                  <div class="item-info">
                    <h4>
                      <a href="public/user/product_detail.php?id=<?= htmlspecialchars($id, ENT_QUOTES) ?>"
                         style="color:inherit; text-decoration:none;">
                        <?= htmlspecialchars($item['name'], ENT_QUOTES) ?>
                      </a>
                    </h4>
                    <span class="item-id">Mã SP: <?= htmlspecialchars($id, ENT_QUOTES) ?></span>
                    <br>
                    <a class="item-remove"
                       href="public/user/cart.php?action=remove&id=<?= urlencode($id) ?>"
                       onclick="return confirm('Xoá sản phẩm này?')">
                      Xoá
                    </a>
                  </div>
                </div>
              </td>
              <td class="price-col">
                <?= number_format((float)$item['price'], 0, ',', '.') ?>₫
              </td>
              <td>
                <div class="qty-wrapper">
                  <input class="qty-input"
                         type="number"
                         name="quantities[<?= htmlspecialchars($id, ENT_QUOTES) ?>]"
                         value="<?= (int)$item['qty'] ?>" min="1">
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
        <span><?= number_format($discount, 0, ',', '.') ?>₫</span>
      </div>

      <div class="summary-row total">
        <span>Tổng cộng</span>
        <span><?= number_format($total, 0, ',', '.') ?>₫</span>
      </div>

      <a class="btn-checkout" href="public/user/checkout.php">Thanh toán</a>
      <a class="btn-continue" href="<?= $BACK_URL ?>">Tiếp tục mua hàng</a>
    </aside>
  <?php endif; ?>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
