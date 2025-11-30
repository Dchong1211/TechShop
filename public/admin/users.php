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
    <title>Quản lý Người dùng</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                    <a href="?controller=customer&action=add" class="btn btn-primary">Thêm Người dùng Mới</a>

                    <!-- SEARCH BOX -->
                    <form method="GET" class="search-box">
                        <input type="hidden" name="controller" value="customer">
                        <input type="hidden" name="action" value="dashboard">
                        <!-- Lỗi khai báo biến $keyword -->
                        <input type="text" name="keyword"
                            placeholder="Tìm kiếm theo Tên / Email..."
                            value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="btn btn-search">Tìm</button>
                    </form>
                </div>--
            </div>

            <div class="card-body">
                <div class="user-table-container">

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Người dùng</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Ngày đăng ký</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u['ten_khachhang']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><?= date("d/m/Y", strtotime($u['created_at'])) ?></td>

                                        <td>
                                            <?php if ($u['status'] == 1): ?>
                                                <span class="status status-active">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="status status-inactive">Khóa</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="action-buttons">
                                            <a href="?controller=customer&action=edit&id=<?= $u['id'] ?>" class="btn btn-edit">
                                                Chỉnh sửa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding:20px;">
                                        Không tìm thấy khách hàng nào.
                                    </td>
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