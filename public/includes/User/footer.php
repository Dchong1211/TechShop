<?php
$ADDITIONAL_BODY_END_CONTENT = $ADDITIONAL_BODY_END_CONTENT ?? '';
?>
  
<footer class="main-footer" role="contentinfo">
  <div class="footer-container">
    <div class="footer-grid">
      
      <div class="footer-column">
        <h4>Về Techshop</h4>
        <ul>
          <li><a href="#">Giới thiệu Techshop</a></li>
          <li><a href="#">Tin tức công nghệ</a></li>
          <li><a href="#">Tuyển dụng</a></li>
          <li><a href="#">Liên hệ & Góp ý</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>Hỗ trợ khách hàng</h4>
        <ul>
          <li><a href="#">Chính sách bảo hành</a></li>
          <li><a href="#">Chính sách đổi trả</a></li>
          <li><a href="#">Hướng dẫn mua hàng online</a></li>
          <li><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>Dịch vụ & Giải pháp</h4>
        <ul>
          <li><a href="#">Xây dựng cấu hình PC</a></li>
          <li><a href="#">Lắp đặt phòng net trọn gói</a></li>
          <li><a href="#">Bảo trì doanh nghiệp</a></li>
          <li><a href="#">Giải pháp camera an ninh</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>Thông tin liên hệ</h4>
        <p><strong>Hotline:</strong> 1900.0099</p>
        <p><strong>Email:</strong> hotro@techshop.vn</p>
        <p><strong>Địa chỉ:</strong> 123 ABC, P. XYZ, Nha Trang, Khánh Hòa</p>
      </div>

      <div class="footer-column">
        <h4>Kết nối với chúng tôi</h4>
        <div class="social-icons">
          <!-- (icons giữ nguyên như bạn gửi) -->
        </div>
        <h4 style="margin-top: 10px;">Phương thức thanh toán</h4>
        <div class="payment-methods">
          <span>VISA</span>
          <span>MasterCard</span>
          <span>MoMo</span>
          <span>VNPay</span>
        </div>
      </div>

    </div>
    <div class="footer-bottom-bar">
      <p>© <?= date('Y') ?> Techshop. Tất cả các quyền được bảo lưu.</p>
    </div>
  </div>
</footer>

<div id="sidebar-overlay"></div>

<?= $ADDITIONAL_BODY_END_CONTENT ?>


<!-- 🟢 THÊM Ở ĐÂY: JS CHUNG CHO USER (SIDEBAR + MEGAMENU + MENU ẨN HIỆN) -->
<script src="public/assets/js/user.js?v=9999"></script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('theme-toggle');
    if (toggleButton) {
      toggleButton.addEventListener('click', function() {
        let currentTheme = document.documentElement.getAttribute('data-theme');
        let newTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        const darkThemeLink = document.getElementById('dark-theme-link');
        if (newTheme === 'dark') {
          if (!darkThemeLink) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.id = 'dark-theme-link';
            link.href = 'public/assets/css/cssUser/dark_theme.css?v=1';
            document.head.appendChild(link);
          }
        } else {
          if (darkThemeLink) {
            darkThemeLink.remove();
          }
        }
      });
    }
  });
</script>

</body>
</html>
