<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/forms.css">
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/orders.css"> 
    
    <style>
        .order-summary-card {
            display: flex;
            justify-content: space-around;
            background-color: #f7f9fb;
            padding: 20px 0;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h4 {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .summary-item p {
            font-size: 1.6rem;
            font-weight: bold;
            color: var(--text-color);
        }
        .section-header {
            font-size: 1.5rem;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #dcdfe1;
        }
        .info-card h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--text-color);
        }
        .info-card p {
            margin-bottom: 10px;
            font-size: 0.95rem;
        }
        .info-card strong {
            display: inline-block;
            width: 120px;
            color: #7f8c8d;
        }
        .product-list-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        .product-list-table th, .product-list-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .product-list-table th {
            background-color: var(--light-bg);
            color: var(--text-color);
        }
        .total-summary {
            text-align: right;
            margin-top: 20px;
        }
        .total-summary p {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .total-summary strong {
            font-size: 1.4rem;
            color: var(--danger-color);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Tech Shop</h2>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Quản lý Sản phẩm</a>
        <a href="orders.php" class="active">Quản lý Đơn hàng</a>
        <a href="users.php">Quản lý Người dùng</a>
        <a href="login.php" style="margin-top: 50px;">Đăng xuất</a>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Chi tiết Đơn hàng #2024001</h1>
            <p>Ngày đặt: 12/11/2025 | Trạng thái: <span class="status status-pending">Chờ xử lý</span></p>
        </header>

        <div class="form-container">
            
            <div class="order-summary-card">
                <div class="summary-item">
                    <h4>Tổng tiền</h4>
                    <p>3.500.000 VNĐ</p>
                </div>
                <div class="summary-item">
                    <h4>Sản phẩm</h4>
                    <p>2 loại</p>
                </div>
                <div class="summary-item">
                    <h4>Thanh toán</h4>
                    <p>COD</p>
                </div>
            </div>

            <h3 class="section-header">Thông tin Khách hàng & Vận chuyển</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h3>Khách hàng</h3>
                    <p><strong>Tên:</strong> Nguyễn Văn A</p>
                    <p><strong>Email:</strong> nguyenvana@example.com</p>
                    <p><strong>Điện thoại:</strong> 0987-654-321</p>
                </div>
                <div class="info-card">
                    <h3>Vận chuyển</h3>
                    <p><strong>Địa chỉ:</strong> Số 123, Đường ABC, Phường 10, Quận XYZ, TP.HCM</p>
                    <p><strong>Ghi chú:</strong> Vui lòng giao hàng sau 5 giờ chiều.</p>
                    <p><strong>Phương thức:</strong> Giao hàng tiêu chuẩn</p>
                </div>
            </div>
            
            <h3 class="section-header">Danh sách Sản phẩm</h3>
            <div class="product-list-table-wrapper">
                <table class="product-list-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá (Đơn vị)</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bàn phím cơ DareU</td>
                            <td>1.000.000 VNĐ</td>
                            <td>1</td>
                            <td>1.000.000 VNĐ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="total-summary">
                <p>Phí vận chuyển: <span>30.000 VNĐ</span></p>
                <p>Giảm giá: <span>0 VNĐ</span></p>
                <p><strong>TỔNG THANH TOÁN: <strong style="color: var(--danger-color);">13.530.000 VNĐ</strong></strong></p>
            </div>

            <h3 class="section-header" style="margin-top: 40px;">Cập nhật Trạng thái</h3>
            <form action="orders.php" method="POST" class="form-group update-status-form">
                <input type="hidden" name="order_id" value="2024001">

                <div class="form-group row-group">
                    <div class="col-6">
                        <label for="new_status">Chọn Trạng thái mới</label>
                        <select id="new_status" name="new_status" class="status-select">
                            <option value="pending" selected>Chờ xử lý</option>
                            <option value="processing">Đang đóng gói</option>
                            <option value="shipping">Đang giao hàng</option>
                            <option value="completed">Đã hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-6" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-submit-update" style="width: 100%;">Lưu Trạng thái</button>
                    </div>
                </div>

            </form>

            <div class="form-actions" style="border-top: none; padding-top: 0;">
                <a href="orders.php" class="btn btn-cancel">❮ Quay lại Danh sách</a>
            </div>
        </div>

    </div>

</body>
</html>