<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <?php require_once __DIR__ . '/../../app/helpers/CSRF.php'; ?>
    <meta name="csrf-token" content="<?= CSRF::token(); ?>">
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
                    <h5 class="card-title">Quản lý Đơn hàng</h5>
                    <div class="top-actions filter-actions" style="margin-bottom: 0;">
                        <div class="filter-group">
                            <label for="status-filter">Lọc theo Trạng thái:</label>
                            <select id="status-filter">
                                <option value="">Tất cả</option>
                                <option value="pending">Chờ xử lý</option>
                                <option value="processing">Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                        
                        <div class="search-box">
                            <input type="text" placeholder="Tìm kiếm theo Mã đơn hàng / Khách hàng...">
                            <button class="btn btn-search">Tìm</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="order-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#2024004</td>
                                    <td>Phạm Thị D</td>
                                    <td>01/11/2025</td>
                                    <td>5.000.000 VNĐ</td>
                                    <td>Chuyển khoản</td>
                                    <td><span class="status status-cancelled">Đã hủy</span></td>
                                    <td class="action-buttons">
                                        <a href="order_detail.php?id=2024004" class="btn btn-detail">Xem chi tiết</a>
                                        <button class="btn btn-delete">Xóa</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>

            </div>
        </main>
    </div>

    <script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>