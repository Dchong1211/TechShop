<?php
// (File: navbar.php)
?>
<nav class="main-nav">
  <div class="nav-inner">

    <div class="nav-item nav-item-toggle">
      <button id="sidebar-toggle-btn">
        <span class="hamburger-icon">
          <span></span><span></span><span></span>
        </span>
        Danh mục
      </button>
    </div>

    <div class="nav-item">
      <a href="public/user/index.php">Trang chủ</a>
    </div>
    <div class="nav-item">
      <a href="public/user/product.php?cate=laptop">Laptop</a>
    </div>
    <div class="nav-item">
      <a href="public/user/product.php?cate=pc">PC - Linh Kiện</a>
    </div>
    <div class="nav-item">
      <a href="public/user/product.php?cate=gear">Gaming Gear</a>
    </div>
    <div class="nav-item">
      <a href="#">Tin tức</a>
    </div>

  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Code xử lý nút hamburger (cho mobile) ---
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarMobile = document.querySelector('.category-sidebar'); 

    if(sidebarToggleBtn && sidebarOverlay && sidebarMobile) {
        const closeSidebar = () => document.body.classList.remove('sidebar-open');
        sidebarToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.body.classList.toggle('sidebar-open');
        });
        sidebarOverlay.addEventListener('click', closeSidebar);
        sidebarMobile.addEventListener('click', (e) => e.stopPropagation());
    }

    // --- Code xử lý submenu Mobile (Accordion) ---
    document.querySelectorAll('.category-sidebar .sidebar-item-with-submenu > a').forEach(button => {
        button.addEventListener('click', function (event) {
            if (window.innerWidth <= 991) {
                event.preventDefault(); // Chặn link trên mobile để mở menu
                const parentItem = this.closest('.sidebar-item-with-submenu');
                parentItem.classList.toggle('submenu-open');
            }
        });
    });

    // ============================================================
    // === FIX LỖI NHẤP NHÁY: DÙNG TIMEOUT (DELAY) CHO DESKTOP ===
    // ============================================================
    if (window.innerWidth > 991) {
        const menuItems = document.querySelectorAll('.nav-item-has-megamenu');
        let activeTimeout; // Biến lưu bộ đếm thời gian
        let currentActivePanel = null;
        let currentActiveItem = null;

        // Hàm tắt toàn bộ menu
        function closeAllMenus() {
            document.querySelectorAll('.sidebar-megamenu-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item-has-megamenu').forEach(i => i.classList.remove('megamenu-hover'));
            currentActivePanel = null;
            currentActiveItem = null;
        }

        // 1. Xử lý khi di chuột vào ITEM (Laptop, PC...)
        menuItems.forEach(item => {
            const targetId = item.getAttribute('data-megamenu-target');
            const panel = document.getElementById(targetId);

            item.addEventListener('mouseenter', function() {
                // Nếu chuột quay lại item, hủy lệnh tắt (nếu có)
                clearTimeout(activeTimeout);

                // Tắt menu cũ nếu đang mở cái khác
                if (currentActiveItem && currentActiveItem !== item) {
                    currentActiveItem.classList.remove('megamenu-hover');
                    if (currentActivePanel) currentActivePanel.classList.remove('active');
                }

                // Bật menu mới
                item.classList.add('megamenu-hover');
                if (panel) {
                    panel.classList.add('active');
                    currentActivePanel = panel;
                }
                currentActiveItem = item;
            });

            // Khi chuột rời khỏi Item -> Chờ 200ms rồi mới tắt
            item.addEventListener('mouseleave', function() {
                activeTimeout = setTimeout(closeAllMenus, 200); // Delay 200ms
            });
        });

        // 2. Xử lý khi di chuột vào BẢNG MENU CON (Panel)
        const allPanels = document.querySelectorAll('.sidebar-megamenu-panel');
        allPanels.forEach(panel => {
            // Khi chuột vào bảng menu -> Hủy lệnh tắt (giữ menu mở)
            panel.addEventListener('mouseenter', function() {
                clearTimeout(activeTimeout);
            });

            // Khi chuột rời bảng menu -> Chờ 200ms rồi tắt
            panel.addEventListener('mouseleave', function() {
                activeTimeout = setTimeout(closeAllMenus, 200);
            });
        });
    }
});
</script>