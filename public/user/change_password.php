<?php
// public/user/change_password.php
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

$PAGE_TITLE = 'Đổi mật khẩu';

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
    <div class="account-main-header">
      <h1>Đổi mật khẩu</h1>
    </div>

    <!-- FE only, BE sau này gắn action thực tế -->
    <form class="account-form" action="" method="post">
      <div class="form-group">
        <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
        <input
          id="current_password"
          name="current_password"
          type="password"
          class="form-input"
          required
        >
      </div>

      <div class="form-group">
        <label class="form-label" for="new_password">Mật khẩu mới</label>
        <input
          id="new_password"
          name="new_password"
          type="password"
          class="form-input"
          required
        >
      </div>

      <div class="form-group">
        <label class="form-label" for="confirm_password">Nhập lại mật khẩu mới</label>
        <input
          id="confirm_password"
          name="confirm_password"
          type="password"
          class="form-input"
          required
        >
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          Lưu thay đổi
        </button>
        <a href="public/user/profile.php" class="btn btn-outline">
          Hủy
        </a>
      </div>
    </form>
  </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
