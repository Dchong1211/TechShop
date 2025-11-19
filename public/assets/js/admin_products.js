document.addEventListener('DOMContentLoaded', () => {
    loadProducts();

    // Xử lý tìm kiếm
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', (e) => {
        const keyword = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#product-list tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
});

async function loadProducts() {
    const tbody = document.getElementById('product-list');
    
    try {
        const res = await fetch('/TechShop/public/api/products');
        const data = await res.json();

        if (data.success) {
            renderTable(data.data);
        } else {
            tbody.innerHTML = `<tr><td colspan="7" style="color:red; text-align:center">${data.message}</td></tr>`;
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" style="color:red; text-align:center">Lỗi kết nối server!</td></tr>`;
    }
}

function renderTable(products) {
    const tbody = document.getElementById('product-list');
    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center">Không có sản phẩm nào.</td></tr>';
        return;
    }

    tbody.innerHTML = products.map(p => `
        <tr>
            <td>${p.id}</td>
            <td>
                <img src="public/assets/images/${p.image || 'placeholder.png'}" 
                     style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
            </td>
            <td>${p.name}</td>
            <td>${getCategoryName(p.category_id)}</td>
            <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p.price)}</td>
            <td>
                <span class="status ${p.stock > 0 ? 'status-active' : 'status-inactive'}">
                    ${p.stock > 0 ? 'Còn hàng' : 'Hết hàng'}
                </span>
            </td>
            <td class="action-buttons">
                <a href="public/admin/edit_products.php?id=${p.id}" class="btn btn-edit">Sửa</a>
                <button onclick="deleteProduct(${p.id})" class="btn btn-delete">Xóa</button>
            </td>
        </tr>
    `).join('');
}

// Helper: Map category_id sang tên (Nếu API không trả về tên, ta tạm map cứng hoặc gọi thêm API categories)
function getCategoryName(id) {
    const cats = { '1': 'Laptop', '2': 'PC', '3': 'Gear', '4': 'Phụ kiện' };
    return cats[id] || 'Khác';
}

async function deleteProduct(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm ID: ' + id + '?')) return;

    try {
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
            alert('Đã xóa thành công!');
            loadProducts(); // Reload lại bảng
        } else {
            alert('Lỗi: ' + data.message);
        }
    } catch (err) {
        alert('Lỗi kết nối khi xóa!');
    }
}