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
            <a href="#" aria-label="Facebook" title="Facebook">
              <svg fill="currentColor" viewBox="0 0 24 24" width="24" height="24"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"></path></svg>
            </a>
            <a href="#" aria-label="YouTube" title="YouTube">
              <svg fill="currentColor" viewBox="0 0 24 24" width="24" height="24"><path d="M21.58 7.19c-.23-.86-.91-1.54-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42c-.86.23-1.54.91-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81c.23.86.91 1.54 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42c.86-.23 1.54-.91 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81zM9.5 15.5V8.5l6 3.5-6 3.5z"></path></svg>
            </a>
            <a href="#" aria-label="TikTok" title="TikTok">
              <svg fill="currentColor" viewBox="0 0 24 24" width="24" height="24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-2.43.05-4.84-.36-6.93-1.93-2.22-1.69-3.34-4.21-3.33-6.84.05-1.02.36-2.02.84-2.89.69-1.28 1.79-2.3 3.19-2.96.95-.45 2-.63 3.02-.63.01 2.9.01 5.79-.01 8.68-.01 1.03-.49 2.01-1.23 2.72-1.12 1.05-2.73 1.39-4.24 1.05-.1-.02-.17-.06-.25-.09-.09-.04-.18-.08-.26-.13-.09-.06-.18-.12-.26-.19-.07-.06-.14-.13-.21-.21-.06-.07-.12-.14-.18-.21-.05-.06-.1-.13-.15-.19-.04-.06-.09-.12-.13-.18-.03-.06-.07-.12-.1-.18-.02-.06-.05-.12-.07-.18s-.04-.13-.06-.19c-.02-.06-.04-.13-.05-.19-.01-.06-.03-.13-.04-.19-.01-.06-.02-.13-.03-.19s-.02-.13-.02-.2c-.01-.06-.01-.13-.02-.19s-.01-.13-.01-.2c0-.06-.01-.13-.01-.19s0-.13-.01-.2c0-.06 0-.13-.01-.19s0-.13-.01-.2v-8.68c.02-2.11 1.33-4 3.22-4.9.11-.05.21-.09.32-.14 1.25-.51 2.6-.74 3.91-.71z"></path></svg>
            </a>
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
</body>
</html>