<?php
    require_once __DIR__ . '/../../app/helpers/CSRF.php';
    $csrf = CSRF::token();
    requireAdmin();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản phẩm Mới</title>

    <meta name="csrf-token" content="<?= $csrf ?>">

    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

<div class="app-wrapper">

    <?php 
        $active_page = 'products'; 
        include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
    ?>

    <main class="main-content">

        <div class="card">
            <form action="/TechShop/admin/products/add" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="product-form">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">

                <div class="card-header">
                    <h5 class="card-title">Thêm Sản phẩm Mới</h5>
                </div>

                <div class="card-body">

                    <!-- Tên sản phẩm -->
                    <div class="form-group">
                        <label for="ten_sp">Tên Sản phẩm <span class="required">*</span></label>
                        <input type="text" id="ten_sp" name="ten_sp" required placeholder="Nhập tên sản phẩm" class="form-control">
                    </div>

                    <!-- Danh mục -->
                    <div class="form-group">
                        <label for="id_dm">Danh mục <span class="required">*</span></label>
                        <select id="id_dm" name="id_dm" required class="form-control">
                            <option value="">Chọn danh mục</option>
                            <option value="1">Laptop</option>
                            <option value="2">Điện thoại</option>
                            <option value="3">Phụ kiện</option>
                        </select>
                    </div>

                    <!-- Giá & Giá khuyến mãi -->
                    <div class="form-group row-group">
                        <div class="col-4">
                            <label for="gia">Giá bán (VNĐ) <span class="required">*</span></label>
                            <input type="number" id="gia" name="gia" required min="1000" class="form-control">
                        </div>

                        <div class="col-4">
                            <label for="gia_khuyen_mai">Giá khuyến mãi</label>
                            <input type="number" id="gia_khuyen_mai" name="gia_khuyen_mai" min="0" class="form-control">
                        </div>

                        <div class="col-4">
                            <label for="so_luong_ton">Số lượng tồn</label>
                            <input type="number" id="so_luong_ton" name="so_luong_ton" min="0" value="0" class="form-control">
                        </div>
                    </div>

                    <!-- Ảnh -->
                    <div class="form-group">
                        <label for="hinh_anh">Hình ảnh sản phẩm <span class="required">*</span></label>
                        <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*" required class="form-control">
                    </div>

                    <!-- Mô tả ngắn -->
                    <div class="form-group">
                        <label for="mo_ta_ngan">Mô tả ngắn</label>
                        <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="2" class="form-control"></textarea>
                    </div>

                    <!-- Chi tiết -->
                    <div class="form-group">
                        <label for="chi_tiet">Mô tả chi tiết</label>
                        <textarea id="chi_tiet" name="chi_tiet" rows="6" class="form-control"></textarea>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="1">Đang bán</option>
                            <option value="0">Ngừng bán</option>
                        </select>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/admin/products" class="btn btn-secondary">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary">Lưu Sản phẩm</button>
                </div>

            </form>
        </div>

    </main>

</div>

<script src="../assets/js/admin.js"></script>

</body>
</html>
