<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/models/OrderModel.php';
require_once __DIR__ . '/../../app/models/UserModel.php';

$csrf = CSRF::token();
requireAdmin();

$orderModel = new OrderModel();
$userModel = new UserModel();

// Lấy danh sách khách hàng để chọn
$users = $userModel->getAllCustomers();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đơn hàng Mới</title>
    <meta name="csrf-token" content="<?= $csrf ?>">

    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .row-group {
            display: flex;
            gap: 15px;
        }
        .row-group .col-6 {
            flex: 1;
        }
        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary { background-color: #1890ff; color: #fff; }
        .btn-secondary { background-color: #ccc; color: #333; }
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
            <form action="/TechShop/public/admin/orders/add" 
                  method="POST" 
                  class="order-form" 
                  id="addOrderForm">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">

                <div class="card-header">
                    <h5 class="card-title">Thêm Đơn hàng Mới</h5>
                </div>

                <div class="card-body">

                    <!-- Khách hàng -->
                    <div class="form-group">
                        <label for="customer_id">Khách hàng <span class="required">*</span></label>
                        <select id="customer_id" name="customer_id" required class="form-control">
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['ho_ten']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Thông tin người nhận -->
                    <div class="form-group">
                        <label for="recipient_name">Người nhận <span class="required">*</span></label>
                        <input type="text" id="recipient_name" name="recipient_name" required class="form-control" placeholder="Nhập tên người nhận">
                    </div>

                    <div class="form-group row-group">
                        <div class="col-6">
                            <label for="recipient_phone">Số điện thoại <span class="required">*</span></label>
                            <input type="text" id="recipient_phone" name="recipient_phone" required class="form-control" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-6">
                            <label for="total_amount">Tổng tiền (VNĐ) <span class="required">*</span></label>
                            <input type="number" id="total_amount" name="total_amount" required class="form-control" placeholder="Nhập tổng tiền">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="delivery_address">Địa chỉ giao hàng <span class="required">*</span></label>
                        <textarea id="delivery_address" name="delivery_address" required class="form-control" placeholder="Nhập địa chỉ giao hàng"></textarea>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái <span class="required">*</span></label>
                        <select id="status" name="status" required class="form-control">
                            <option value="cho_xac_nhan">Chờ xác nhận</option>
                            <option value="da_xac_nhan">Đã xác nhận</option>
                            <option value="dang_giao">Đang giao</option>
                            <option value="da_giao">Đã giao</option>
                            <option value="huy">Đã hủy</option>
                        </select>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/public/admin/orders" class="btn btn-secondary">Hủy bỏ</a>
                    <button type="button" class="btn btn-primary" onclick="confirmAddOrder()">Lưu Đơn hàng</button>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
    function confirmAddOrder() {
        const form = document.getElementById('addOrderForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if(confirm("Bạn có chắc chắn muốn thêm đơn hàng mới?")) {
            const btn = form.querySelector('.btn-primary');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Đang xử lý...';
            btn.disabled = true;

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Thêm đơn hàng thành công!');
                    window.location.href = '/TechShop/public/admin/orders';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server!');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    }
</script>

</body>
</html>
