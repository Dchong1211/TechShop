<?php
declare(strict_types=1);
session_start();
define('BASE_PATH', dirname(__DIR__));

// Lấy danh sách đơn hàng (Sau này sẽ SELECT từ DB)
$orders = $_SESSION['orders'] ?? [];

function money_vn($v){ return number_format((float)$v,0,',','.').' ₫'; }

$PAGE_TITLE = 'Đơn hàng của tôi';

ob_start();
?>
  <style>
    .page-orders { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
    .card { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { background: #f9f9f9; text-align: left; padding: 12px; font-weight: 600; color: #555; border-bottom: 2px solid #eee; }
    td { padding: 15px 12px; border-bottom: 1px solid #eee; vertical-align: middle; color: #333; }
    
    .btn { 
        display: inline-block; padding: 6px 14px; border-radius: 6px; 
        text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.2s; 
    }
    .btn-view { background: #1677ff; color: #fff; border: 1px solid #1677ff; }
    .btn-view:hover { background: #0958d9; }
    
    .btn-home { background: #d70018; color: #fff; padding: 10px 20px; font-size: 15px; }
    
    .empty-box { text-align: center; padding: 40px; }
    .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-pending { background: #e6f7ff; color: #096dd9; }
    .status-cancelled { background: #fff1f0; color: #cf1322; }
  </style>
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/header.php';
?>

<main class="page-orders">
    <section class="card">
        <h2 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:15px;">Lịch sử đơn hàng</h2>
        
        <?php if (empty($orders)): ?>
            <div class="empty-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="80" alt="Empty" style="opacity:0.5; margin-bottom:15px;">
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
                        <!-- Đảo ngược mảng để đơn mới nhất lên đầu -->
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
                                    <!-- Link trỏ đúng về orders_detail.php -->
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
include BASE_PATH . '/includes/footer.php';
?>