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
            <form action="/TechShop/public/admin/products/update" method="POST" enctype="multipart/form-data" id="updateForm">

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
                        <label>Ảnh</label>
                        <div class="img-preview-box">
                            <img id="imgPreview" src="<?= htmlspecialchars($sp['hinh_anh']) ?>" 
                                 onerror="this.src='https://via.placeholder.com/150?text=No+Image'" 
                                 alt="Preview">
                        </div>
                        <input type="file" name="hinh_anh_file" id="imgInput" accept="image/*" class="form-control"
                               onchange="previewFile(this)">
                        <input type="hidden" name="hinh_anh" id="imgUrl" value="<?= htmlspecialchars($sp['hinh_anh']) ?>">
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

                        <button type="submit" class="btn btn-primary" onclick="confirmUpdate()">
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
<script src="/TechShop/public/assets/js/admin_products.js"></script>
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

    // Hàm hiển thị ảnh xem trước từ file
    function previewFile(input) {
        const file = input.files[0];
        const preview = document.getElementById('imgPreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            const formData = new FormData();
            formData.append('image', file);

            fetch('https://api.imgbb.com/1/upload?key=0275912d6d120a13546bea4af61d67e2', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.data && data.data.url) {
                    document.getElementById('imgUrl').value = data.data.url;
                } else {
                    alert('Upload ảnh thất bại!');
                }
            })
            .catch(() => alert('Lỗi upload ảnh!'));
        }
    }

    // Hàm xác nhận xóa
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này vĩnh viễn?")) {
            
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
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server!');
            });
        }
    }

    function confirmUpdate() {
        const form = document.getElementById('updateForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (confirm("Bạn có chắc chắn muốn lưu thay đổi?")) {
            const btn = form.querySelector('button[type="button"].btn-primary');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';
            btn.disabled = true;

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Cập nhật thành công!');
                    window.location.href = '/TechShop/public/admin/products';
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