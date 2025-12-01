<?php
// public/user/edit_profile.php
session_start();
define('BASE_PATH', dirname(__DIR__));

// Bảo vệ: chưa đăng nhập thì đá về trang chủ
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

// XỬ LÝ SUBMIT FORM: tạm thời chỉ update SESSION, BE khác sẽ lo phần DB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name !== '') {
        $_SESSION['user']['name'] = $name;
    }
    if ($email !== '') {
        $_SESSION['user']['email'] = $email;
    }

    // quay về trang profile để xem kết quả
    header('Location: profile.php');
    exit;
}

$PAGE_TITLE = 'Chỉnh sửa thông tin';

// Nhúng CSS chung
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
      <a href="public/user/profile.php">
        <img class="nav-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/user.svg" alt="">
        <span>Thông tin cá nhân</span>
      </a>
      <a href="public/user/edit_profile.php" class="active">
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

  <!-- MAIN: FORM CHỈNH SỬA -->
  <section class="account-card account-main">
    <div class="account-main-header">
      <h1>Chỉnh sửa thông tin</h1>
    </div>

    <!-- action="" => submit lên đúng /public/user/edit_profile.php -->
    <form class="account-form" action="" method="post">
      <div class="form-group">
        <label class="form-label" for="name">Họ và tên</label>
        <input
          id="name"
          name="name"
          type="text"
          class="form-input"
          value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES) ?>"
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
          value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES) ?>"
          required
        >
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <img class="btn-icon" src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/outline/device-floppy.svg" alt="">
          <span>Lưu thay đổi</span>
        </button>
        <a href="profile.php" class="btn btn-ghost">
          Hủy
        </a>
      </div>
    </form>
  </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
