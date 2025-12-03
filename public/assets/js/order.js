document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1️⃣ Cập nhật trạng thái
    document.body.addEventListener('change', e => {
        const select = e.target.closest('.order-status-select');
        if (!select) return;

        const orderId = select.dataset.id;
        const newStatus = select.value;

        fetch(`/TechShop/public/admin/orders.php?action=update_status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken
            },
            body: `id=${orderId}&trang_thai_don=${encodeURIComponent(newStatus)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) alert('Cập nhật trạng thái thành công!');
            else alert('Cập nhật thất bại: ' + data.message);
        })
        .catch(err => console.error(err));
    });

    // 2️⃣ Xóa đơn hàng
    document.body.addEventListener('click', e => {
        const btn = e.target.closest('.btn-delete-order');
        if (!btn) return;

        const orderId = btn.dataset.id;
        if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) return;

        fetch(`/TechShop/public/admin/orders.php?action=delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken
            },
            body: `id=${orderId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Xóa thành công!');
                const row = btn.closest('tr');
                if (row) row.remove();
            } else {
                alert('Xóa thất bại: ' + data.message);
            }
        })
        .catch(err => console.error(err));
    });

    // 3️⃣ Tìm kiếm đơn hàng
    const searchInput = document.querySelector('#order-search-input');
    const tableBody = document.querySelector('#orders-table-body');

    if (searchInput && tableBody) {
        let timeout = null;
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.trim();
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                if (q.length < 2) {
                    location.reload();
                    return;
                }

                fetch(`/TechShop/public/admin/orders.php?action=search&q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        if (!data.length) {
                            tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:20px;">Không tìm thấy đơn hàng nào.</td></tr>`;
                            return;
                        }

                        data.forEach(order => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${order.id}</td>
                                <td>${order.id_khach_hang}</td>
                                <td>${Number(order.tong_tien).toLocaleString('vi-VN')}₫</td>
                                <td>${order.ten_nguoi_nhan}</td>
                                <td>${order.sdt_nguoi_nhan}</td>
                                <td>${order.dia_chi_giao_hang}</td>
                                <td>
                                    <select class="order-status-select" data-id="${order.id}">
                                        <option value="cho_xac_nhan" ${order.trang_thai_don==='cho_xac_nhan'?'selected':''}>Chờ xác nhận</option>
                                        <option value="dang_giao" ${order.trang_thai_don==='dang_giao'?'selected':''}>Đang giao</option>
                                        <option value="da_giao" ${order.trang_thai_don==='da_giao'?'selected':''}>Đã giao</option>
                                        <option value="huy" ${order.trang_thai_don==='huy'?'selected':''}>Đã hủy</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="/TechShop/public/admin/edit_order.php?id=${order.id}" class="btn btn-edit">Chỉnh sửa</a>
                                    <button data-id="${order.id}" class="btn btn-delete-order">Xóa</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(err => console.error(err));
            }, 300);
        });
    }
});
