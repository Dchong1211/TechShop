<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

// 1. LẤY DỮ LIỆU
$orders = $_SESSION['orders'] ?? [];

// 2. TÌM ĐƠN HÀNG
$viewId = $_GET['id'] ?? '';
$viewIndex = -1; 

if ($viewId) {
    foreach ($orders as $index => $o) {
        if ($o['id'] === $viewId) {
            $viewIndex = $index;
            break;
        }
    }
}

// --- SỬA 1: PHP REDIRECT ---
// Nếu không tìm thấy đơn -> Về danh sách
if ($viewIndex === -1) {
    // Đang ở cùng thư mục public/user, chỉ cần gọi tên file là được
    header('Location: orders.php'); 
    exit;
}

$view = $orders[$viewIndex];

// 3. XỬ LÝ HỦY ĐƠN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $_SESSION['orders'][$viewIndex]['status'] = 'cancelled';
    
    // --- SỬA 2: RELOAD TRANG ---
    // Dùng tên file trực tiếp vì đang ở cùng thư mục
    header("Location: orders_detail.php?id=" . urlencode($viewId));
    exit;
}

if (!function_exists('money_vn')) {
    function money_vn($v){ return number_format((float)$v,0,',','.').' ₫'; }
}

$PAGE_TITLE = 'Chi tiết đơn hàng #' . $view['id'];

ob_start();
?>
<link rel="stylesheet" href="/TechShop/public/assets/css/cssUser/orders_detail.css">

<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();
include BASE_PATH . '/includes/header.php';
?>

<main class="page-detail">
    <section>
        <div class="card">
            <div class="detail-header">
                <div>
                    <h2>Chi tiết đơn hàng #<?= htmlspecialchars($view['id'], ENT_QUOTES) ?></h2>
                    <span>Ngày đặt: <?= htmlspecialchars($view['created_at'], ENT_QUOTES) ?></span>
                </div>
                <div>
                    <?php if(isset($view['status']) && $view['status'] === 'cancelled'): ?>
                        <span class="status-badge status-cancelled">Đã hủy</span>
                    <?php else: ?>
                        <span class="status-badge status-pending">Đang xử lý</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-info-grid">
                <div>
                    <h4>Thông tin nhận hàng</h4>
                    <div class="info-row"><span>Người nhận:</span> <strong><?= htmlspecialchars($view['fullname'], ENT_QUOTES) ?></strong></div>
                    <div class="info-row"><span>Điện thoại:</span> <strong><?= htmlspecialchars($view['phone'], ENT_QUOTES) ?></strong></div>
                    <div class="info-row"><span>Địa chỉ:</span> <span><?= htmlspecialchars($view['address'], ENT_QUOTES) ?></span></div>
                </div>
                <div>
                    <h4>Thanh toán</h4>
                    <div class="info-row">
                        <span>Phương thức:</span> 
                        <strong><?= $view['payment'] === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' ?></strong>
                    </div>
                    <?php if(!empty($view['note'])): ?>
                        <div class="info-row"><span>Ghi chú:</span> <span style="font-style:italic"><?= htmlspecialchars($view['note'], ENT_QUOTES) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>

            <h4 style="margin-bottom: 10px;">Sản phẩm đã mua</h4>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="width: 80px; text-align: center;">SL</th>
                        <th style="width: 130px; text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($view['items'] as $it):
                        $line = (float)$it['price'] * (int)$it['qty']; 
                    ?>
                    <tr>
                        <td>
                            <div class="product-name"><?= htmlspecialchars($it['name'], ENT_QUOTES) ?></div>
                            <div class="product-price"><?= money_vn($it['price']) ?></div>
                        </td>
                        <td style="text-align: center;">x<?= (int)$it['qty'] ?></td>
                        <td style="text-align: right; font-weight:600;"><?= money_vn($line) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <aside>
        <div class="card summary-total">
            <h3 class="summary-header">Tổng giá trị</h3>
            <div class="row"><span>Tạm tính</span><strong><?= money_vn($view['subtotal']) ?></strong></div>
            <div class="row"><span>Phí vận chuyển</span><span><?= $view['shipping'] === 0 ? 'Miễn phí' : money_vn($view['shipping']) ?></span></div>
            <?php if($view['discount'] > 0): ?>
                <div class="row"><span>Giảm giá</span><span style="color:green">- <?= money_vn($view['discount']) ?></span></div>
            <?php endif; ?>
            <div class="row total"><span>Tổng cộng</span><span><?= money_vn($view['total']) ?></span></div>
        </div>

        <div class="card" style="padding: 15px;">
            <div class="btn-group">
                <a href="/TechShop/public/user/index.php" class="btn btn-primary">Mua thêm sản phẩm khác</a>
                
                <?php if(!isset($view['status']) || $view['status'] !== 'cancelled'): ?>
                    <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                    </form>
                <?php endif; ?>

                <a href="/TechShop/public/user/orders.php" class="btn btn-secondary">← Quay lại danh sách đơn</a>
            </div>
        </div>
    </aside>
</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>