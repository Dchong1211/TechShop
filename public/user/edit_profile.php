<?php
// public/user/edit_profile.php
session_start();
define('BASE_PATH', dirname(__DIR__));

// Bảo vệ trang
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

$PAGE_TITLE = 'Chỉnh sửa thông tin';
ob_start();
?>
<style>
  .page{max-width:960px;margin:24px auto;padding:0 16px;display:grid;grid-template-columns:240px 1fr;gap:24px}
  .card{background:#fff;border:1px solid #eee;border-radius:10px;padding:16px}
  .nav-list a{display:block;padding:10px;text-decoration:none;color:#333;border-radius:6px; margin-bottom: 4px;}
  .nav-list a.active{background:#f0f6ff;color:#1677ff;font-weight:600}
  .nav-list a:hover{background:#f5f5f5}
  .field{margin-bottom:12px}
  .field label{display:block;font-weight:600;margin-bottom:6px}
  .field input{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
  .btn{display:inline-block;background:#1677ff;color:#fff;text-decoration:none;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
  #form-message{margin-top:12px;padding:10px;border-radius:8px;display:none;}
  #form-message.success{background:#f6ffed;border:1px solid #b7eb8f;color:#52c41a;}
  #form-message.error{background:#fff1f0;border:1px solid #ffa39e;color:#ff4d4f;}
</style>
<?php
$ADDITIONAL_HEAD_CONTENT = ob_get_clean();
include BASE_PATH . '/includes/header.php';
?>

<main class="page">
  <aside>
    <div class="card">
      <nav class="nav-list">
        <a href="public/user/profile.php">Thông tin cá nhân</a>
        <a href="public/user/edit_profile.php" class="active">Chỉnh sửa thông tin</a>
        <a href="public/user/change_password.php">Đổi mật khẩu</a>
        <a href="public/user/orders.php">Quản lý đơn hàng</a>
        <a href="public/user/logout.php" style="color: #ff4d4f;">Đăng xuất</a>
      </nav>
    </div>
  </aside>
  
  <section class="card">
    <h2>Chỉnh sửa thông tin</h2>
    
    <form id="edit-profile-form">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" readonly disabled style="background:#f5f5f5">
      </div>
      <div class="field">
        <label for="name">Họ và tên</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>" required>
      </div>
      
      <div id="form-message"></div>
      <button class="btn" type="submit" style="margin-top: 12px;">Lưu thay đổi</button>
    </form>
  </section>
</main>

<?php ob_start(); ?>
<script>
document.getElementById('edit-profile-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const form = this;
  const messageDiv = document.getElementById('form-message');
  const formData = new FormData(form);
  
  messageDiv.style.display = 'none';

  // Giả định API nằm ở /app/api/update_profile.php
  fetch('../app/api/update_profile.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      messageDiv.textContent = 'Cập nhật thành công!';
      messageDiv.className = 'success';
      messageDiv.style.display = 'block';
      
      // Quan trọng: Vì session được cập nhật ở backend,
      // khi người dùng F5 lại trang profile, họ sẽ thấy tên mới.
      // (Không cần cập nhật session ở frontend)
      
    } else {
      messageDiv.textContent = data.message || 'Đã xảy ra lỗi. Vui lòng thử lại.';
      messageDiv.className = 'error';
      messageDiv.style.display = 'block';
    }
  })
  .catch(error => {
    messageDiv.textContent = 'Lỗi kết nối. Vui lòng thử lại.';
    messageDiv.className = 'error';
    messageDiv.style.display = 'block';
  });
});
</script>
<?php
$ADDITIONAL_BODY_END_CONTENT = ob_get_clean();
include BASE_PATH . '/includes/footer.php';
?>