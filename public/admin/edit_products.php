<?php
require_once __DIR__ . '/../../app/controllers/ProductController.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';

$csrf = CSRF::token();
requireAdmin();
// Lấy ID từ URL
$id = $_GET['id'] ?? 0;

// Lấy dữ liệu sản phẩm
$controller = new ProductController();
$res = $controller->detail($id);

if (!$res["success"]) {
    die("<h2 style='color:red; text-align:center;'>Không tìm thấy sản phẩm!</h2>");
}

$sp = $res["data"];   // <<< QUAN TRỌNG – PHẢI CÓ DÒNG NÀY
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>

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
            <form action="/TechShop/admin/products/update" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="product-form">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $sp['id'] ?>">

                <div class="card-header">
                    <h5 class="card-title">✍️ Sửa Thông tin Sản phẩm</h5>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>ID Sản phẩm</label>
                        <input type="text" value="<?= $sp['id'] ?>" readonly class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="ten_sp">Tên Sản phẩm *</label>
                        <input type="text" id="ten_sp" name="ten_sp" 
                               value="<?= $sp['ten_sp'] ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="id_dm">Danh mục *</label>
                        <select id="id_dm" name="id_dm" required class="form-control">
                            <option value="1" <?= $sp['id_dm']==1?'selected':'' ?>>Laptop</option>
                            <option value="2" <?= $sp['id_dm']==2?'selected':'' ?>>Điện thoại</option>
                            <option value="3" <?= $sp['id_dm']==3?'selected':'' ?>>Phụ kiện</option>
                        </select>
                    </div>

                    <div class="form-group row-group">
                        <div class="col-4">
                            <label>Giá gốc (VNĐ)</label>
                            <input type="number" name="gia" min="1000"
                                   value="<?= $sp['gia'] ?>" class="form-control">
                        </div>

                        <div class="col-4">
                            <label>Giá khuyến mãi</label>
                            <input type="number" name="gia_khuyen_mai" min="0"
                                   value="<?= $sp['gia_khuyen_mai'] ?>" class="form-control">
                        </div>

                        <div class="col-4">
                            <label>Tồn kho</label>
                            <input type="number" name="so_luong_ton" 
                                   value="<?= $sp['so_luong_ton'] ?>" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái</label>
                        <div class="radio-group">
                            <input type="radio" id="st1" name="trang_thai" value="1"
                                   <?= $sp['trang_thai']==1?'checked':'' ?>>
                            <label for="st1">Hoạt động</label>

                            <input type="radio" id="st0" name="trang_thai" value="0"
                                   <?= $sp['trang_thai']==0?'checked':'' ?>>
                            <label for="st0">Ngừng bán</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="mo_ta_ngan" class="form-control"><?= $sp['mo_ta_ngan'] ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="chi_tiet" rows="6" class="form-control"><?= $sp['chi_tiet'] ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/admin/products" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>

            </form>
        </div>

    </main>

</div>
<script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
