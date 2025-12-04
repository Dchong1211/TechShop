<?php
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

requireAdmin();
$csrf = CSRF::token();

$id = $_GET['id'] ?? 0;

$controller = new CustomerController();
$res = $controller->detail($id);

if (!$res['success']) {
    die("<h2 style='color:red; text-align:center;'>Không tìm thấy người dùng!</h2>");
}

$user = $res['data'];
$avatar = $user['avatar'] ?: 'https://placehold.co/150';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa Người dùng</title>

    <!-- CSRF META -->
    <meta name="csrf-token" content="<?= $csrf ?>">

    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .avatar-upload-container { display:flex; gap:16px; align-items:center; margin-bottom:20px; }
        .avatar-preview { width:80px; height:80px; border-radius:50%; overflow:hidden; border:2px solid #ddd; }
        .avatar-preview img { width:100%; height:100%; object-fit:cover; }
        .card-footer { display:flex; justify-content:space-between; align-items:center; margin-top:20px; }
        .right-actions { display:flex; gap:10px; }
        .status-verified { color:#22c55e; font-weight:600; }
        .status-unverified { color:#ef4444; font-weight:600; }
    </style>
</head>

<body>
<div class="app-wrapper">

    <?php
        $active_page = 'users';
        include __DIR__ . '/../includes/Admin/layout_sidebar.php';
    ?>

    <main class="main-content">
        <div class="card">

            <form id="updateForm" method="POST" class="form-group-wrapper">

                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="card-header">
                    <h5 class="card-title">
                        Chỉnh sửa Người dùng: <?= htmlspecialchars($user['ho_ten']) ?> (ID: <?= $user['id'] ?>)
                    </h5>
                </div>

                <div class="card-body">

                    <!-- AVATAR -->
                    <div class="form-group">
                        <label>Avatar URL</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview">
                                <img id="avatarPreview" src="<?= htmlspecialchars($avatar) ?>" alt="Avatar">
                            </div>
                            <input type="text" name="avatar" id="avatarInput"
                                   class="form-control"
                                   value="<?= htmlspecialchars($user['avatar']) ?>"
                                   placeholder="Dán link avatar..."
                                   oninput="previewAvatar(this.value)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="ho_ten" class="form-control"
                               value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="mat_khau" class="form-control"
                               placeholder="Để trống nếu không muốn thay đổi">
                    </div>

                    <div class="form-group">
                        <label>Ngày tạo</label>
                        <input type="text" class="form-control"
                               value="<?= date('d/m/Y H:i', strtotime($user['ngay_tao'])) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email xác minh</label>
                        <span class="<?= $user['email_verified'] ? 'status-verified' : 'status-unverified' ?>">
                            <?= $user['email_verified'] ? 'Đã xác minh' : 'Chưa xác minh' ?>
                        </span>
                    </div>

                    <div class="form-group row-group">

                        <div class="col-6">
                            <label>Vai trò</label>
                            <select name="vai_tro" class="form-control">
                                <option value="khach" <?= $user['vai_tro']=='khach'?'selected':'' ?>>Khách hàng</option>
                                <option value="admin" <?= $user['vai_tro']=='admin'?'selected':'' ?>>Quản trị viên</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label>Trạng thái</label>
                            <select name="trang_thai" class="form-control">
                                <option value="1" <?= $user['trang_thai']==1?'selected':'' ?>>Hoạt động</option>
                                <option value="0" <?= $user['trang_thai']==0?'selected':'' ?>>Đã khóa</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <a href="/TechShop/admin/users" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>

                    <div class="right-actions">
                        <button type="button" id="deleteUserBtn" class="btn btn-delete">
                            <i class="bi bi-trash"></i> Xóa tài khoản
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu Thay đổi
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </main>
</div>



<script>
/* =============== PREVIEW AVATAR =============== */
function previewAvatar(url) {
    document.getElementById("avatarPreview").src =
        url && url.trim() ? url : "https://placehold.co/150";
}



/* ============================================================
   UPDATE USER — fetch đúng đường dẫn router
============================================================ */
document.getElementById("updateForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData(this);

    formData.append("csrf_token", csrf);

    fetch("/TechShop/admin/users/update", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) window.location.reload();
    })
    .catch(() => alert("Lỗi kết nối server!"));
});



/* ============================================================
   DELETE USER — fetch đúng router (/TechShop/admin/users/delete)
============================================================ */
document.getElementById("deleteUserBtn").addEventListener("click", function () {

    if (!confirm("Bạn chắc chắn muốn xóa người dùng này?")) return;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const id = document.querySelector('input[name="id"]').value;

    const formData = new FormData();
    formData.append("csrf_token", csrf);
    formData.append("id", id);

    fetch("/TechShop/admin/users/delete", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) window.location.href = "/TechShop/admin/users";
    })
    .catch(() => alert("Lỗi kết nối server!"));
});
</script>

<script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
