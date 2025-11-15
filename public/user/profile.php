<?php
// public/user/profile.php
session_start();
define('BASE_PATH', dirname(__DIR__));

// Bảo vệ trang: Nếu chưa đăng nhập, đá về trang chủ
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Lấy thông tin user từ session
$user = $_SESSION['user'];

$PAGE_TITLE = 'Trang cá nhân';
ob_start();
?>
<style>
  .page{max-width:960px;margin:24px auto;padding:0 16px;display:grid;grid-template-columns:240px 1fr;gap:24px}
  .card{background:#fff;border:1px solid #eee;border-radius:10px;padding:16px}
  .nav-list a{display:block;padding:10px;text-decoration:none;color:#333;border-radius:6px; margin-bottom: 4px;}
  .nav-list a.active{background:#f0f6ff;color:#1677ff;font-weight:600}
  .nav-list a:hover{background:#f5f5f5}
  .field{margin-bottom:12px}
  .field label{display:block;font-weight:600;margin-bottom:6px; color: #555;}
  .field-value{font-size:16px;padding:8px 0;}
  .btn{display:inline-block;background:#1677ff;color:#fff;text-decoration:none;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
</style>
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();
include BASE_PATH . '/includes/header.php';
?>

<main class="page">
  <aside>
    <div class="card">
      <nav class="nav-list">
        <a href="public/user/profile.php" class="active">Thông tin cá nhân</a>
        <a href="public/user/edit_profile.php">Chỉnh sửa thông tin</a>
        <a href="public/user/change_password.php">Đổi mật khẩu</a>
        <a href="public/user/orders.php">Quản lý đơn hàng</a>
        <a href="public/user/logout.php" style="color: #ff4d4f;">Đăng xuất</a>
      </nav>
    </div>
  </aside>
  
  <section class="card">
    <h2>Thông tin cá nhân</h2>
    
    <div class="field">
      <label>Họ và tên</label>
      <div class="field-value"><?= htmlspecialchars($user['name'], ENT_QUOTES) ?></div>
    </div>
    <div class="field">
      <label>Email</label>
      <div class="field-value"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></div>
    </div>
    
    <a class="btn" href="public/user/edit_profile.php" style="margin-top: 12px;">Chỉnh sửa</a>
  </section>
</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>