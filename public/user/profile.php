<?php
// public/user/profile.php
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

$PAGE_TITLE = 'Trang cá nhân';

// nhúng CSS account
ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/account.css?v=2">
<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/User/header.php';
?>

<main class="account-page">
  <!-- SIDEBAR -->
  <aside class="account-card account-sidebar">
    <h2>Tài khoản</h2>
    <nav class="account-nav">
      <a href="public/user/profile.php" class="active">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/user.svg" alt="">
        <span>Thông tin cá nhân</span>
      </a>
      <a href="public/user/edit_profile.php">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/pencil.svg" alt="">
        <span>Chỉnh sửa thông tin</span>
      </a>
      <a href="public/user/change_password.php">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/lock.svg" alt="">
        <span>Đổi mật khẩu</span>
      </a>
      <a href="public/user/orders.php">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/package.svg" alt="">
        <span>Quản lý đơn hàng</span>
      </a>
      <a href="public/user/logout.php" class="logout">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/power.svg" alt="">
        <span>Đăng xuất</span>
      </a>
    </nav>
  </aside>

  <!-- MAIN: THÔNG TIN TÀI KHOẢN -->
  <section class="account-card account-main">
    <header class="profile-header">
      <div class="account-avatar">
        <?php
          $initial = mb_substr($user['name'] ?? 'T', 0, 1, 'UTF-8');
          echo htmlspecialchars(mb_strtoupper($initial, 'UTF-8'), ENT_QUOTES);
        ?>
      </div>

      <div>
        <div class="profile-name">
          <?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES) ?>
        </div>
        <div class="account-meta">
          <span class="badge member">Member</span>
          <span class="badge verified">Đã xác minh</span>
        </div>
      </div>
    </header>

    <div class="profile-grid">
      <!-- Cột trái: thông tin tài khoản -->
      <div>
        <h3 class="profile-section-title">Thông tin tài khoản</h3>

        <div class="info-row">
          <div class="info-label">Họ và tên</div>
          <div class="info-value">
            <?= htmlspecialchars($user['name'] ?? 'Chưa cập nhật', ENT_QUOTES) ?>
          </div>
        </div>

        <div class="info-row">
          <div class="info-label">Email</div>
          <div class="info-value">
            <?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật', ENT_QUOTES) ?>
          </div>
        </div>

        <div class="profile-actions">
          <a href="public/user/edit_profile.php" class="btn btn-primary">
            <img class="btn-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/pencil.svg" alt="">
            <span>Chỉnh sửa thông tin</span>
          </a>

          <a href="public/user/change_password.php" class="btn btn-outline">
            <img class="btn-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/lock.svg" alt="">
            <span>Đổi mật khẩu</span>
          </a>
        </div>
      </div>

      <!-- Cột phải: thông tin khác -->
      <div>
        <h3 class="profile-section-title">Khác</h3>

        <div class="info-row">
          <div class="info-label">Ngày tham gia</div>
          <div class="info-value">
            <?= htmlspecialchars($user['created_at'] ?? '01/12/2025', ENT_QUOTES) ?>
          </div>
        </div>

        <div class="info-row">
          <div class="info-label">Số điện thoại</div>
          <div class="info-value">
            <?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật', ENT_QUOTES) ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
