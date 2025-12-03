<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/models/OrderModel.php';
require_once __DIR__ . '/../../app/models/UserModel.php';

requireAdmin();
$csrf = CSRF::token();

$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    die("Không có ID đơn hàng!");
}

$orderModel = new OrderModel();
$order = $orderModel->getById((int)$orderId);

if (!$order) {
    die("Không tìm thấy đơn hàng với ID $orderId");
}

// Lấy thông tin khách hàng
$userModel = new UserModel();
$customer = $userModel->getById($order['id_khach_hang']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?></title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-footer { display:flex; justify-content:space-between; align-items:center; padding-top:20px; border-top:1px solid #eee; }
        .right-actions { display:flex; gap:10px; align-items:center; }
        .update-status-form { display:flex; align-items:center; gap:10px; margin:0; }
        .update-status-form .form-group { margin-bottom:0; }
        .status { font-weight:bold; }
        .status-pending { color: orange; }
        .status-processing { color: blue; }
        .status-shipping { color: goldenrod; }
        .status-completed { color: green; }
        .status-cancelled { color: red; }
    </style>
</head>
<body>

<div class="app-wrapper">
    <?php 
    $active_page = 'orders'; 
    include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
    ?>

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?></h5>
                <div class="order-header-meta">
                    <span>Ngày đặt: <?= date('d/m/Y', strtotime($order['ngay_dat_hang'])) ?></span>
                    <span>Trạng thái: 
                        <span class="status status-<?= htmlspecialchars($order['trang_thai_don']) ?>">
                            <?= htmlspecialchars($order['trang_thai_don']) ?>
                        </span>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="order-summary-card">
                    <div class="summary-item">
                        <h4>Tổng tiền</h4>
                        <p><?= number_format($order['tong_tien'],0,',','.') ?> VNĐ</p>
                    </div>
                    <div class="summary-item">
                        <h4>Thanh toán</h4>
                        <p><?= htmlspecialchars($order['phuong_thuc_thanh_toan']) ?></p>
                    </div>
                </div>

                <h3 class="sub-section-title">Thông tin & Sản phẩm</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Khách hàng</h3>
                        <p><?= htmlspecialchars($customer['ho_ten'] ?? 'N/A') ?></p>
                        <p>Email: <?= htmlspecialchars($customer['email'] ?? 'N/A') ?></p>
                        <p>SDT: <?= htmlspecialchars($customer['sdt'] ?? 'N/A') ?></p>
                    </div>
                    <div class="info-card">
                        <h3>Vận chuyển</h3>
                        <p>Người nhận: <?= htmlspecialchars($order['ten_nguoi_nhan'] ?? 'N/A') ?></p>
                        <p>SDT: <?= htmlspecialchars($order['sdt_nguoi_nhan'] ?? 'N/A') ?></p>
                        <p>Địa chỉ: <?= htmlspecialchars($order['dia_chi_giao_hang'] ?? 'N/A') ?></p>

                    </div>
                </div>

                <div class="total-summary">
                    <p><strong>TỔNG THANH TOÁN: 
                        <span class="total-amount"><?= number_format($order['tong_tien'],0,',','.') ?> VNĐ</span>
                    </strong></p>
                </div>
            </div> 

            <div class="card-footer">
                <a href="orders.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>

                <div class="right-actions">
                    <form action="orders.php" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($order['id']) ?>">
                        <button type="submit" class="btn btn-delete">
                            <i class="bi bi-trash"></i> Xóa đơn
                        </button>
                    </form>

                    <form action="orders.php" method="POST" class="update-status-form">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($order['id']) ?>">
                        <div class="form-group">
                            <select name="trang_thai_don" class="form-control" style="min-width: 150px;">
                                <option value="pending" <?= $order['trang_thai_don']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                                <option value="processing" <?= $order['trang_thai_don']=='processing'?'selected':'' ?>>Đang đóng gói</option>
                                <option value="shipping" <?= $order['trang_thai_don']=='shipping'?'selected':'' ?>>Đang giao hàng</option>
                                <option value="completed" <?= $order['trang_thai_don']=='completed'?'selected':'' ?>>Đã hoàn thành</option>
                                <option value="cancelled" <?= $order['trang_thai_don']=='cancelled'?'selected':'' ?>>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>

        </div> 
    </main> 
</div> 

<script src="../assets/js/admin.js"></script>
</body>
</html>
