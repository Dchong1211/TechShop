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

// CSS cụm tài khoản
ob_start();
?>
<link rel="stylesheet" href="public/assets/css/cssUser/account.css?v=2">
<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean();

include BASE_PATH . '/includes/User/header.php';
?>

<main class="account-page">
  <?php include __DIR__ . '/sidebar_account.php'; ?>

  <section class="account-card account-main">
    <div class="profile-header">
      <div class="account-avatar">
        <?php
          $initial = mb_substr($user['name'] ?? $user['full_name'] ?? 'T', 0, 1, 'UTF-8');
          echo htmlspecialchars(mb_strtoupper($initial, 'UTF-8'));
        ?>
      </div>
      <div>
        <h1><?= htmlspecialchars($user['name'] ?? $user['full_name'] ?? 'User') ?></h1>
        <div class="account-meta">
          <span class="badge member">Member</span>
          <span class="badge verified">Đã xác minh</span>
        </div>
      </div>
    </div>

    <div class="profile-grid">
      <!-- Cột trái: Thông tin tài khoản -->
      <div>
        <div class="profile-section-title">Thông tin tài khoản</div>

        <div class="info-row">
          <div class="info-label">Họ và tên</div>
          <div class="info-value">
            <?= htmlspecialchars($user['name'] ?? $user['full_name'] ?? 'Chưa cập nhật') ?>
          </div>
        </div>

        <div class="info-row">
          <div class="info-label">Email</div>
          <div class="info-value">
            <?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?>
          </div>
        </div>
      </div>

      <!-- Cột phải: Thông tin khác -->
      <div>
        <div class="profile-section-title">Khác</div>

        <div class="info-row">
          <div class="info-label">Ngày tham gia</div>
          <div class="info-value">
            <?php
              $created = $user['created_at'] ?? $user['createdAt'] ?? null;
              echo $created ? htmlspecialchars(date('d/m/Y', strtotime($created))) : 'Chưa cập nhật';
            ?>
          </div>
        </div>

        <div class="info-row">
          <div class="info-label">Số điện thoại</div>
          <div class="info-value">
            <?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?>
          </div>
        </div>
      </div>
    </div>

    <div class="profile-actions">
      <a href="public/user/edit_profile.php" class="btn btn-primary">
        Chỉnh sửa thông tin
      </a>
      <a href="public/user/change_password.php" class="btn btn-outline">
        Đổi mật khẩu
      </a>
    </div>
  </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
