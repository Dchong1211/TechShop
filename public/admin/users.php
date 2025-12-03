<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
require_once __DIR__ . '/../../app/helpers/auth.php';
require_once __DIR__ . '/../../app/models/UserModel.php';

requireAdmin();

$csrf = CSRF::token();
$keyword = $_GET['keyword'] ?? ''; // Khởi tạo biến keyword

$model = new UserModel();

// Nếu có từ khóa tìm kiếm
if (!empty($keyword)) {
    $users = $model->search($keyword);
} else {
    $users = $model->getAllCustomers();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
    <meta name="csrf-token" content="<?= $csrf ?>">
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
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
            <div class="card-header">
                <h5 class="card-title">Quản lý Người dùng</h5>

                <div class="table-actions">
                    <a href="/TechShop/public/admin/add_users.php" class="btn btn-primary">Thêm Người dùng Mới</a>

                    <!-- SEARCH BOX -->
                    <form method="GET" class="search-box" action="/TechShop/admin/users">
                        <input type="text" name="keyword"
                               placeholder="Tìm kiếm theo Tên / Email..."
                               value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="btn btn-search">Tìm</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="user-table-container">

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Người dùng</th>
                                <th>Email</th>
                                <th>Email xác minh</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u['id']) ?></td>
                                        <td><?= htmlspecialchars($u['ho_ten']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td>
                                            <?php if ($u['email_verified']): ?>
                                                <span style="color: green; font-weight: bold;">Đã xác minh</span>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">Chưa xác minh</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $u['trang_thai'] 
                                                ? '<span class="status-active">Hoạt động</span>' 
                                                : '<span class="status-inactive">Khóa</span>' ?>
                                        </td>
                                        <td>
                                            <a href="/TechShop/public/admin/edit_user.php?id=<?= $u['id'] ?>" class="btn btn-edit">Chỉnh sửa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:20px;">Không tìm thấy người dùng nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
</div>

<script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
