<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .right-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .update-status-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .update-status-form .form-group {
            margin-bottom: 0;
        }
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
                    <h5 class="card-title">Chi tiết Đơn hàng #2024001</h5>
                    <div class="order-header-meta">
                        <span>Ngày đặt: 12/11/2025</span>
                        <span>Trạng thái: <span class="status status-pending">Chờ xử lý</span></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="order-summary-card">
                        <div class="summary-item"><h4>Tổng tiền</h4><p>3.500.000 VNĐ</p></div>
                         <div class="summary-item"><h4>Sản phẩm</h4><p>2 loại</p></div>
                         <div class="summary-item"><h4>Thanh toán</h4><p>COD</p></div>
                    </div>

                    <h3 class="sub-section-title">Thông tin & Sản phẩm</h3>
                    <div class="info-grid">
                        <div class="info-card"><h3>Khách hàng</h3><p>Nguyễn Văn A...</p></div>
                        <div class="info-card"><h3>Vận chuyển</h3><p>Địa chỉ...</p></div>
                    </div>
                    
                    <div class="total-summary">
                        <p><strong>TỔNG THANH TOÁN: <strong class="total-amount">13.530.000 VNĐ</strong></strong></p>
                    </div>

                </div> 

                <div class="card-footer">
                    <a href="orders.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                    
                    <div class="right-actions">
                        <form action="orders.php" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="2024001">
                            <button type="submit" class="btn btn-delete">
                                <i class="bi bi-trash"></i> Xóa đơn
                            </button>
                        </form>

                        <form action="orders.php" method="POST" class="update-status-form">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="2024001">
                            
                            <div class="form-group">
                                <select name="trang_thai_don" class="form-control" style="min-width: 150px;">
                                    <option value="pending" selected>Chờ xử lý</option>
                                    <option value="processing">Đang đóng gói</option>
                                    <option value="shipping">Đang giao hàng</option>
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
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