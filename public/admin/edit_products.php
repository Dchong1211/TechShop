<?php
require_once __DIR__ . '/../../app/controllers/ProductController.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';

$csrf = CSRF::token();
requireAdmin();
$id = $_GET['id'] ?? 0;

$controller = new ProductController();
$res = $controller->detail($id);

if (!$res["success"]) {
    die("<h2 style='color:red; text-align:center;'>Không tìm thấy sản phẩm!</h2>");
}

$sp = $res["data"];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>

    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        .img-preview-box {
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f9f9f9;
            margin-bottom: 10px;
        }
        .img-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .right-actions {
            display: flex;
            gap: 10px;
        }
    </style>
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
                  class="product-form" id="updateForm">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $sp['id'] ?>">

                <div class="card-header">
                    <h5 class="card-title">✍️ Chỉnh sửa sản phẩm: <?= htmlspecialchars($sp['ten_sp']) ?></h5>
                </div>

                <div class="card-body">
                    <div class="form-group row-group">
                        <div class="col-6">
                            <label for="ten_sp">Tên Sản phẩm *</label>
                            <input type="text" id="ten_sp" name="ten_sp" value="<?= htmlspecialchars($sp['ten_sp']) ?>" required class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="id_dm">Danh mục *</label>
                            <select id="id_dm" name="id_dm" required class="form-control">
                                <option value="1" <?= $sp['id_dm']==1?'selected':'' ?>>Laptop</option>
                                <option value="2" <?= $sp['id_dm']==2?'selected':'' ?>>Điện thoại</option>
                                <option value="3" <?= $sp['id_dm']==3?'selected':'' ?>>Phụ kiện</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Link Hình ảnh (URL)</label>
                        
                        <div class="img-preview-box">
                            <img id="imgPreview" src="<?= htmlspecialchars($sp['hinh_anh']) ?>" 
                                 onerror="this.src='https://via.placeholder.com/150?text=No+Image'" 
                                 alt="Preview">
                        </div>
                        
                        <input type="text" name="hinh_anh" id="imgInput" 
                               class="form-control" 
                               value="<?= htmlspecialchars($sp['hinh_anh']) ?>" 
                               placeholder="Nhập đường dẫn hình ảnh..."
                               oninput="previewUrl(this.value)">
                    </div>

                    <div class="form-group row-group">
                        <div class="col-4">
                            <label>Giá gốc (VNĐ)</label>
                            <input type="number" name="gia" min="1000" value="<?= $sp['gia'] ?>" class="form-control">
                        </div>
                        <div class="col-4">
                            <label>Giá khuyến mãi</label>
                            <input type="number" name="gia_khuyen_mai" min="0" value="<?= $sp['gia_khuyen_mai'] ?>" class="form-control">
                        </div>
                        <div class="col-4">
                            <label>Tồn kho</label>
                            <input type="number" name="so_luong_ton" value="<?= $sp['so_luong_ton'] ?>" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="1" <?= $sp['trang_thai']==1?'selected':'' ?>>Đang bán</option>
                            <option value="0" <?= $sp['trang_thai']==0?'selected':'' ?>>Ngừng bán</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="mo_ta_ngan" class="form-control" rows="2"><?= htmlspecialchars($sp['mo_ta_ngan']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="chi_tiet" rows="5" class="form-control"><?= htmlspecialchars($sp['chi_tiet']) ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/public/admin/products" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>

                    <div class="right-actions">
                        <button type="button" class="btn btn-delete" onclick="confirmDelete(<?= $sp['id'] ?>)">
                            <i class="bi bi-trash"></i> Xóa sản phẩm
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </main>

</div>

<form id="deleteForm" action="/TechShop/public/admin/products/delete" method="POST" style="display:none;">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <input type="hidden" name="id" id="deleteId">
</form>

<script src="/TechShop/public/assets/js/admin.js"></script>
<script>
    // Hàm hiển thị ảnh xem trước từ URL text
    function previewUrl(url) {
        const preview = document.getElementById('imgPreview');
        if(url && url.trim() !== '') {
            preview.src = url;
        } else {
            preview.src = 'https://via.placeholder.com/150?text=No+Image';
        }
    }

    // Hàm xác nhận xóa
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này vĩnh viễn?")) {
            document.getElementById('deleteId').value = id;
            
            const csrf = document.querySelector('input[name="csrf"]').value;
            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf', csrf);

            fetch('/TechShop/public/admin/products/delete', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Đã xóa thành công!');
                    window.location.href = '/TechShop/public/admin/products';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => alert('Lỗi kết nối server'));
        }
    }
</script>

</body>
</html>