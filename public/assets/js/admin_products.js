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

// Load danh sách
async function loadProducts() {
    const tbody = document.getElementById('productTableBody');

    try {
        const res = await fetch('/TechShop/public/api/products');
        const data = await res.json();

        if (data.success) renderTable(data.data);
        else tbody.innerHTML = `<tr><td colspan="7">${data.message}</td></tr>`;

    } catch {
        tbody.innerHTML = `<tr><td colspan="7">Lỗi kết nối server!</td></tr>`;
    }
}

// Render table
function renderTable(products) {
    const tbody = document.getElementById('productTableBody');

    if (products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">Không có sản phẩm nào.</td></tr>`;
        return;
    }

    tbody.innerHTML = products.map(p => {
        const imagePath = p.hinh_anh;
        const finalSrc = imagePath && (imagePath.startsWith('http') || imagePath.startsWith('https'))
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
                    <a href="/TechShop/public/admin/edit_products.php?id=${p.id}" class="btn btn-edit">Chỉnh sửa</a>
                </td>
            </tr>
        `;
    }).join('');
}


// Helper
function getCategoryName(id) {
    const cats = {1:'Laptop',2:'PC',3:'Gear',4:'Phụ kiện'};
    return cats[id] || 'Khác';
}
function formatPrice(num) {
    return new Intl.NumberFormat('vi-VN', {style:'currency',currency:'VND'}).format(num);
}

// Xóa sản phẩm
async function deleteProduct(id) {
    if (!confirm(`Xóa sản phẩm ID: ${id}?`)) return;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf', csrf);

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
}

async function handleAddProducts(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]')
    const originalText = btn.innerHTML;

    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang xử lý...';
    btn.disable = true;
    const formData = new FormData(form);

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            alert('✅ ' + data.message);
            window.location.href = '/TechShop/public/admin/products';
        } else {
            alert('Lỗi: ' + data.message);
        }
    } catch (err) {
        console.error(err);
        alert('Lỗi kết nối đến máy chủ!');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function handleUpdateProduct(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;

    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';
    btn.disabled = true;

    const formData = new FormData(form);

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        const contentType = res.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            const data = await res.json();
            if (data.success) {
                alert('Cập nhật thành công!');
                window.location.href = '/TechShop/public/admin/products';
            } else {
                alert('Lỗi' + data.message);
            }
        } else {
            const text = await res.text();
            console.error(text);
            alert('Có lỗi xảy ra ở phía Server. Vui lòng kiểm tra console.');
        }

    } catch (err) {
        console.error(err);
        alert('Lỗi kết nối!');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
