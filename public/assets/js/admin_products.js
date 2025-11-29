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

// Render table (ĐÃ SỬA LỖI HIỂN THỊ ẢNH)
function renderTable(products) {
    const tbody = document.getElementById('productTableBody');

    if (products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">Không có sản phẩm nào.</td></tr>`;
        return;
    }

    tbody.innerHTML = products.map(p => {
        // LOGIC MỚI: Kiểm tra nếu hinh_anh là URL (chứa http/https), dùng trực tiếp.
        // Ngược lại, dùng đường dẫn cục bộ.
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
                         style="width:50px;height:50px; object-fit:cover;">
                </td>

                <td>${p.ten_sp}</td>

                <td>${p.category_name || 'Không có danh mục'}</td>

                <td>${formatPrice(Number(p.gia))}</td>

                <td>${p.so_luong_ton > 0 ? 'Còn hàng' : 'Hết hàng'}</td>

                <td class="action-buttons">
                    <a href="/TechShop/public/admin/edit_products.php?id=${p.id}" class="btn btn-edit">Sửa</a>
                    <button onclick="deleteProduct(${p.id})" class="btn btn-delete">Xóa</button>
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