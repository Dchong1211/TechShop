<?php
// public/user/change_password.php
session_start();
define('BASE_PATH', dirname(__DIR__));

// Bảo vệ trang
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

// ===== KẾT NỐI DB =====
require_once dirname(__DIR__) . '/../app/config/database.php';

$db   = new Database();
$conn = $db->getConnection();

// Biến lưu thông báo
$errors  = [];
$success = '';

// ===== XỬ LÝ POST ĐỔI MẬT KHẨU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = trim($_POST['current_password'] ?? '');
    $new     = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    // 1. Validate cơ bản
    if ($new !== $confirm) {
        $errors[] = 'Mật khẩu mới và nhập lại không khớp.';
    }

    if (strlen($new) < 6) {
        $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    }

    if (empty($errors)) {
        // 2. Lấy hash mật khẩu hiện tại từ DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $stmt->bind_result($passwordHash);
        $hasRow = $stmt->fetch();
        $stmt->close();

        if (!$hasRow) {
            $errors[] = 'Không tìm thấy tài khoản.';
        } else {
            // 3. Kiểm tra mật khẩu hiện tại
            if (!password_verify($current, $passwordHash)) {
                $errors[] = 'Mật khẩu hiện tại không đúng.';
            }
        }

        // 4. Nếu mọi thứ OK -> cập nhật mật khẩu mới
        if (empty($errors)) {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('si', $newHash, $user['id']);

            if ($stmt->execute()) {
                $success = 'Đổi mật khẩu thành công.';
                $stmt->close();

                // Có thể set flash message, rồi redirect về profile
                $_SESSION['flash_success'] = $success;
                header('Location: profile.php');
                exit;
            } else {
                $errors[] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
            }
            $stmt->close();
        }
    }
}

$PAGE_TITLE = 'Đổi mật khẩu';

// CSS chung cho cụm tài khoản
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
        <span class="icon">
          <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="" loading="lazy">
        </span>
        <span>Thông tin cá nhân</span>
      </a>
      <a href="public/user/edit_profile.php">
        <span class="icon">
          <img src="https://cdn-icons-png.flaticon.com/512/1827/1827933.png" alt="" loading="lazy">
        </span>
        <span>Chỉnh sửa thông tin</span>
      </a>
      <a href="public/user/change_password.php" class="active">
        <span class="icon">
          <img src="https://cdn-icons-png.flaticon.com/512/3064/3064155.png" alt="" loading="lazy">
        </span>
        <span>Đổi mật khẩu</span>
      </a>
      <a href="public/user/orders.php">
        <span class="icon">
          <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="" loading="lazy">
        </span>
        <span>Quản lý đơn hàng</span>
      </a>
      <a href="public/user/logout.php" class="logout">
        <span class="icon">
          <img src="https://cdn-icons-png.flaticon.com/512/1828/1828490.png" alt="" loading="lazy">
        </span>
        <span>Đăng xuất</span>
      </a>
    </nav>
  </aside>

  <!-- MAIN -->
  <section class="account-card account-main">
    <div class="account-main-header">
      <h1>Đổi mật khẩu</h1>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
          <p><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success">
        <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    <?php endif; ?>

    <form class="account-form" action="public/user/change_password.php" method="post">
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
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="public/user/profile.php" class="btn btn-outline">Hủy</a>
      </div>
    </form>
  </section>
</main>

<?php
include BASE_PATH . '/includes/User/footer.php';
?>
