// public/assets/js/admin_users.js
import { renderPagination } from './pagination.js';

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            loadUsers(); // search server-side
        });
    }
});

/* ============ LOAD USERS + PAGINATION ============ */
async function loadUsers(page = 1) {
    const tbody  = document.getElementById('userTableBody');
    const search = document.getElementById('searchInput')?.value || "";

    if (!tbody) return;

    try {
        const res = await fetch(
            `/TechShop/admin/users/list?page=${page}&limit=10&search=${encodeURIComponent(search)}`
        );

        const json = await res.json();
        console.log('USERS LIST JSON => ', json); // debug

        if (!json.success) {
            tbody.innerHTML = `<tr><td colspan="8">Không tải được danh sách người dùng</td></tr>`;
            return;
        }

        const users = json.data || [];
        if (users.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8">Không có người dùng nào</td></tr>`;
        } else {
            tbody.innerHTML = "";
            users.forEach(u => {
                const avatar = u.avatar && u.avatar.trim() !== ''
                    ? u.avatar
                    : 'https://placehold.co/50';

                const trangThaiText = u.trang_thai == 1 ? 'Hoạt động' : 'Khóa';

                tbody.innerHTML += `
                    <tr>
                        <td>${u.id}</td>
                        <td>
                            <img src="${avatar}" alt="avatar" class="table-avatar">
                        </td>
                        <td>${u.ho_ten}</td>
                        <td>${u.email}</td>
                        <td>${u.vai_tro}</td>
                        <td>${trangThaiText}</td>
                        <td>${u.ngay_tao}</td>
                        <td>
                            <a href="/TechShop/admin/users/edit?id=${u.id}" class="btn btn-sm btn-primary">
                                Sửa
                            </a>
                        </td>
                    </tr>
                `;
            });
        }

        // phân trang dùng chung với product
        if (json.meta) {
            renderPagination(json.meta, loadUsers);
        }

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="8">Lỗi tải dữ liệu người dùng</td></tr>`;
    }
}
