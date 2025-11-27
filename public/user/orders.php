<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

$orders = $_SESSION['orders'] ?? [];

function money_vn($v){ return number_format((float)$v,0,',','.').' ₫'; }

$PAGE_TITLE = 'Đơn hàng của tôi';

ob_start();
?>
  <link rel="stylesheet" href="public/assets/css/cssUser/orders.css?v=1">
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/User/header.php';
?>

<main class="page-orders">
    <section class="card">
        <h2 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:15px;">Lịch sử đơn hàng</h2>
        
        <?php if (empty($orders)): ?>
            <div class="empty-box">
                <i class="fa-solid fa-box-open" style="font-size: 50px; color: #ddd; margin-bottom: 15px;"></i>
                <p style="color:#666; margin-bottom:20px;">Bạn chưa có đơn hàng nào.</p>
                <a class="btn btn-home" href="public/user/index.php">Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Người nhận</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th style="text-align:right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($orders) as $o): ?>
                            <tr>
                                <td><strong>#<?= htmlspecialchars($o['id'], ENT_QUOTES) ?></strong></td>
                                <td><?= htmlspecialchars($o['created_at'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($o['fullname'], ENT_QUOTES) ?></td>
                                <td style="font-weight:700; color:#d70018;"><?= money_vn($o['total']) ?></td>
                                <td>
                                    <?php if(isset($o['status']) && $o['status'] === 'cancelled'): ?>
                                        <span class="status-badge status-cancelled">Đã hủy</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">Đang xử lý</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right">
                                    <a class="btn btn-view" href="public/user/orders_detail.php?id=<?= urlencode($o['id']) ?>">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>