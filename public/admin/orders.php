
<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/models/OrderModel.php';

$csrf = CSRF::token();
requireAdmin();

$orderModel = new OrderModel();
$orders = $orderModel->all();

// XỬ LÝ AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    switch ($_GET['action']) {
        case 'search':
            $q = $_GET['q'] ?? '';
            echo json_encode($orderModel->search($q));
            exit;

        case 'update_status':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                CSRF::requireToken();
                $id = intval($_POST['id']);
                $status = $_POST['trang_thai_don'] ?? '';
                $ok = $orderModel->updateStatus($id, $status);
                echo json_encode([
                    'success' => $ok,
                    'message' => $ok ? 'Cập nhật thành công' : 'Cập nhật thất bại'
                ]);
                exit;
            }
            break;
        default:
            echo json_encode([]);
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <meta name="csrf-token" content="<?= $csrf ?>">
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                <h5 class="card-title">Quản lý Người dùng</h5>

                <div class="table-actions">
                    <a href="/TechShop/public/admin/add_orders.php" class="btn btn-primary">Thêm Người dùng Mới</a>


                    <!-- SEARCH BOX -->
                    <form method="GET" class="search-box" action="/TechShop/public/admin/orders.php">
                        <input type="text" name="q"
                            placeholder="Tìm kiếm theo mã đơn hàng / tên khách hàng"
                            value="<?= isset($q) ? htmlspecialchars($q) : '' ?>">
                        <button type="submit" class="btn btn-search">Tìm</button>
                    </form>

                </div>
            </div>

            <div class="card-body">
                <div class="order-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền (VNĐ)</th>
                                <th>Người nhận</th>
                                <th>SĐT người nhận</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $o): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($o['id']) ?></td>
                                        <td><?= htmlspecialchars($o['ten_khach']) ?></td>
                                        <td><?= number_format($o['tong_tien'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($o['ten_nguoi_nhan']) ?></td>
                                        <td><?= htmlspecialchars($o['sdt_nguoi_nhan']) ?></td>
                                        <td><?= htmlspecialchars($o['dia_chi_giao_hang']) ?></td>

                                        <td>
                                            <?php
                                                switch ($o['trang_thai_don']) {
                                                    case 'cho_xac_nhan':
                                                        echo '<span style = "color: #a7a730ff; font-weight: bold;">Chờ xác nhận</span>';
                                                        break;
                                                    case 'da_xac_nhan':
                                                        echo '<span style = "color: #a7a730ff; font-weight: bold;">Đã xác nhận</span>';
                                                        break;
                                                    case 'dang_giao':
                                                        echo '<span style = "color: #a7a730ff; font-weight: bold;">Đang giao</span>';
                                                        break;
                                                    case 'da_giao':
                                                        echo '<span style = "color: green; font-weight: bold;">Đã giao</span>';
                                                        break;
                                                    case 'huy':
                                                        echo '<span style = "color: red; font-weight: bold;">Đã hủy</span>';
                                                        break;
                                                }
                                            ?>
                                        </td>

                                        <td>
                                            <a href="/TechShop/public/admin/edit_order.php?id=<?= $o['id'] ?>" class="btn btn-edit">Chỉnh sửa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align:center; padding:20px;">Không tìm thấy đơn hàng nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="/TechShop/public/assets/js/orders.js"></script>
</body>
</html>
