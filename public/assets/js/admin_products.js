import { renderPagination } from './pagination.js';

/* =======================================================
    INIT
======================================================= */
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();

    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', (e) => {
        const keyword = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#productTableBody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
});


/* =======================================================
    LOAD PRODUCTS + PAGINATION
======================================================= */
async function loadProducts(page = 1) {
    const tbody = document.getElementById('productTableBody');
    const search = document.getElementById('searchInput').value || "";

    try {
        const res = await fetch(
            `/TechShop/admin/products/list?page=${page}&limit=5&search=${encodeURIComponent(search)}`
        );

        const json = await res.json();

        if (!json.success) {
            tbody.innerHTML = `<tr><td colspan="7">Không có sản phẩm.</td></tr>`;
            return;
        }

        renderTable(json.data);
        renderPagination(json.meta, "loadProducts");

    } catch (err) {
        console.error("Lỗi:", err);
        tbody.innerHTML = `<tr><td colspan="7">Lỗi kết nối server!</td></tr>`;
    }
}
window.loadProducts = loadProducts;


/* =======================================================
    RENDER TABLE
======================================================= */
function renderTable(products) {
    const tbody = document.getElementById('productTableBody');

    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">Không có sản phẩm nào.</td></tr>`;
        return;
    }

    tbody.innerHTML = products.map(p => {
        const imagePath = p.hinh_anh;
        const finalSrc = imagePath && (imagePath.startsWith('http'))
            ? imagePath
            : `/TechShop/public/uploads/products/${imagePath || 'placeholder.png'}`;

        return `
            <tr>
                <td>${p.id}</td>

                <td>
                    <img src="${finalSrc}"
                         alt="${p.ten_sp}"
                         style="width:50px;height:50px; object-fit:cover; border-radius: 4px;">
                </td>

                <td>${p.ten_sp}</td>
                <td>${p.category_name || 'Không có danh mục'}</td>
                <td>${formatPrice(Number(p.gia))}</td>
                <td>${p.so_luong_ton > 0 ? 'Còn hàng' : 'Hết hàng'}</td>

                <td class="action-buttons">
                    <a href="/TechShop/public/admin/edit_products.php?id=${p.id}" 
                       class="btn btn-edit">Chỉnh sửa</a>
                </td>
            </tr>
        `;
    }).join('');
}


/* =======================================================
    PRICE FORMAT
======================================================= */
function formatPrice(num) {
    return new Intl.NumberFormat('vi-VN', { 
        style: 'currency', 
        currency: 'VND' 
    }).format(num);
}


/* =======================================================
    ADD PRODUCT
======================================================= */
async function handleAddProducts(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const original = btn.innerHTML;

    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang xử lý...';
    btn.disabled = true;

    const formData = new FormData(form);

    try {
        const res = await fetch(form.action, { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            alert('Thêm thành công!');
            window.location.href = '/TechShop/public/admin/products';
        } else {
            alert('Lỗi: ' + data.message);
        }

    } catch {
        alert('Lỗi kết nối!');
    }

    btn.innerHTML = original;
    btn.disabled = false;
}


/* =======================================================
    UPDATE PRODUCT
======================================================= */
async function handleUpdateProduct(e) {
    e.preventDefault();
    const form = e.target;

    const btn = form.querySelector('button[type="submit"]');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';
    btn.disabled = true;

    const formData = new FormData(form);

    try {
        const res = await fetch(form.action, { method: "POST", body: formData });
        const data = await res.json();

        if (data.success) {
            alert('Cập nhật thành công!');
            window.location.href = '/TechShop/public/admin/products';
        } else {
            alert('Lỗi: ' + data.message);
        }

    } catch {
        alert('Lỗi kết nối!');
    }

    btn.innerHTML = original;
    btn.disabled = false;
}


/* =======================================================
    DELETE PRODUCT
======================================================= */
async function deleteProduct(id) {
    if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) return;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf', csrf);

    try {
        const res = await fetch('/TechShop/public/admin/products/delete', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            alert('Đã xóa!');
            loadProducts();
        } else {
            alert('Lỗi: ' + data.message);
        }

    } catch {
        alert('Lỗi server!');
    }
}


/* =======================================================
    PREVIEW URL IMAGE
======================================================= */
function previewUrl(url) {
    const img = document.getElementById('imgPreview');

    if (url && url.trim().length > 5) {
        img.src = url;
        img.style.display = 'block';

        img.onerror = function() {
            this.style.display = 'none';
        };

    } else {
        img.style.display = 'none';
    }
}

