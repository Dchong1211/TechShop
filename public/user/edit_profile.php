<?php
// public/user/edit_profile.php
session_start();
define('BASE_PATH', dirname(__DIR__));

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

$PAGE_TITLE = 'Chỉnh sửa thông tin';

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
      <h1>Chỉnh sửa thông tin</h1>
    </div>

    <!-- FE only: action="" để BE tự xử lý route sau này -->
    <form class="account-form" action="" method="post">
      <div class="form-group">
        <label class="form-label" for="name">Họ và tên</label>
        <input
          id="name"
          name="name"
          type="text"
          class="form-input"
          value="<?= htmlspecialchars($user['name'] ?? $user['full_name'] ?? '') ?>"
          required
        >
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          class="form-input"
          value="<?= htmlspecialchars($user['email'] ?? '') ?>"
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
