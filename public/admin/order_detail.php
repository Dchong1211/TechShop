<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng</title>
    <link rel="stylesheet" href="public/assets/css/cssAdmin/main_admin.css"> 
    <link rel="stylesheet" href="public/assets/css/cssAdmin/detail_order.css"> 
</head>
<body>

    <div class="app-wrapper">
        
        <nav class="top-navbar">
            <div class="navbar-left">
                <a href="index.php" class="navbar-brand">TechShop</a>
                <button class="sidebar-toggle" type="button">☰</button> </div>
            <div class="navbar-search">
                <input type="text" placeholder="Search...">
            </div>
            <div class="navbar-right">
                <button class="theme-toggle" id="theme-toggle" type="button" title="Chuyển đổi Sáng/Tối">
                    <span class="icon-sun">[☀️]</span>
                    <span class="icon-moon">[🌙]</span>
                </button>
                <a href="#" class="nav-icon"><i class="bi bi-bell" style="color: #5e6e82"></i></a>
                <a href="#" class="nav-icon"><i class="bi bi-gear" style="color: #5e6e82"></i></a>
                <a href="#" class="nav-icon user-avatar">[User]</a>
            </div>
        </nav>

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="index.php" class="active">
                            <span class="icon"><i class="bi bi-house" style="color: #5e6e82"></i></span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <span class="icon"><i class="bi bi-box" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <span class="icon"><i class="bi bi-cart" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Đơn hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <span class="icon"><i class="bi bi-people" style="color: #5e6e82"></i></span>
                            <span class="title">Quản lý Người dùng</span>
                        </a>
                    </li>
                    <li class="sidebar-logout">
                        <a href="login.php">
                            <span class="icon"><i class="bi bi-box-arrow-right" style="color: #5e6e82"></i></span>
                            <span class="title">Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

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

                    <h3 class="sub-section-title">Thông tin Khách hàng & Vận chuyển</h3>
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
                    
                    <h3 class="sub-section-title">Danh sách Sản phẩm</h3>
                    <div class="product-list-table-wrapper">
                        <table class="data-table product-list-table">
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
                        <p><strong>TỔNG THANH TOÁN: <strong class="total-amount">13.530.000 VNĐ</strong></strong></p>
                    </div>

                </div> <div class="card-footer">
                    <form action="orders.php" method="POST" class="update-status-form">
                        <input type="hidden" name="order_id" value="2024001">

                        <div class="form-group">
                            <label for="new_status">Cập nhật Trạng thái</label>
                        </div>
                        
                        <div class="form-group">
                            <select id="new_status" name="new_status" class="form-control">
                                <option value="pending" selected>Chờ xử lý</option>
                                <option value="processing">Đang đóng gói</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Lưu Trạng thái</button>
                        </div>

                    </form>
                    
                    <a href="orders.php" class="btn btn-secondary" style="margin-left: auto; align-self: center;">❮ Quay lại</a>
                </div>
                
            </div> </main> 
        
    </div> <script src="/public/assets/js/admin.js"></script>

</body>
</html>