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
            display: none;
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
            <form action="/TechShop/public/admin/products/add" 
            method="POST" class="product-form" id="addForm">

                <input type="hidden" name="csrf" value="<?= $csrf ?>">

                <div class="card-header">
                    <h5 class="card-title">Thêm Sản phẩm Mới</h5>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label for="ten_sp">Tên Sản phẩm <span class="required">*</span></label>
                        <input type="text" id="ten_sp" name="ten_sp" required placeholder="Nhập tên sản phẩm" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="id_dm">Danh mục <span class="required">*</span></label>
                        <select id="id_dm" name="id_dm" required class="form-control">
                            <option value="">Chọn danh mục</option>
                            <option value="1">Laptop</option>
                            <option value="2">Điện thoại</option>
                            <option value="3">Phụ kiện</option>
                        </select>
                    </div>

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

                    <div class="form-group">
                        <label for="mo_ta_ngan">Mô tả ngắn</label>
                        <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="chi_tiet">Mô tả chi tiết</label>
                        <textarea id="chi_tiet" name="chi_tiet" rows="6" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="1">Đang bán</option>
                            <option value="0">Ngừng bán</option>
                        </select>
                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/public/admin/products" class="btn btn-secondary">Hủy bỏ</a>
                    
                    <button type="button" class="btn btn-primary" onclick="confirmAdd()">Lưu Sản phẩm</button>
                </div>

            </form>
        </div>

    </main>

</div>

<script src="/TechShop/public/assets/js/admin.js"></script>
<script src="/TechShop/public/assets/js/admin_products.js"></script>
<script>
    function previewUrl(url) {
        const img = document.getElementById('imgPreview');
        if (url && url.length > 5) {
            img.src = url;
            img.style.display = 'block';
            
            img.onerror = function() {
                this.style.display = 'none';
            };
        } else {
            img.style.display = 'none';
        }
    }
    // Hàm xác nhận Thêm sản phẩm
    function confirmAdd() {
        const form = document.getElementById('addForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (confirm("Bạn có chắc chắn muốn thêm sản phẩm mới này?")) {
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
                if (data.success) {
                    alert('Thêm sản phẩm thành công!');
                    window.location.href = '/TechShop/public/admin/products';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối máy chủ (Vui lòng kiểm tra lại đường dẫn hoặc server)!');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    }
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
</script>

</body>
</html>