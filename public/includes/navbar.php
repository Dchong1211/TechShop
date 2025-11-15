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
    
    // --- Code xử lý nút hamburger (cho menu mobile) ---
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

    // --- Code xử lý submenu (accordion) CỦA SIDEBAR (dùng cho mobile) ---
    document.querySelectorAll('.category-sidebar .sidebar-item-with-submenu > a').forEach(button => {
        button.addEventListener('click', function (event) {
            // Chỉ chạy accordion trên mobile (khi nút hamburger hiển thị)
            if (window.innerWidth <= 991) {
                const parentItem = this.closest('.sidebar-item-with-submenu');
                const isSubmenuOpen = parentItem.classList.contains('submenu-open');

                if (this.getAttribute('href') === '#' || (parentItem.querySelector('.sidebar-submenu') && isSubmenuOpen)) {
                    event.preventDefault();
                }

                if (isSubmenuOpen) {
                    parentItem.classList.remove('submenu-open');
                } else {
                    parentItem.classList.add('submenu-open');
                }
            }
        });
    });

    // ===============================================
    // === CODE MỚI: XỬ LÝ MEGA MENU (CHO DESKTOP) ===
    // ===============================================
    const megamenuItems = document.querySelectorAll('.nav-item-has-megamenu');
    const megamenuPanels = document.querySelectorAll('.sidebar-megamenu-panel');
    const sidebarContainer = document.querySelector('.col-left-sidebar');
    let hoverTimeout;

    // Chỉ chạy logic này trên Desktop
    if (window.innerWidth > 991 && sidebarContainer) {
        
        megamenuItems.forEach(item => {
            const targetId = item.getAttribute('data-megamenu-target');
            const panel = document.getElementById(targetId);

            item.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                // 1. Tắt hết các mục khác
                hideAllMegaMenus();
                
                // 2. Thêm class hover cho mục này (để đổi màu)
                item.classList.add('megamenu-hover');
                
                // 3. Hiển thị panel tương ứng
                if (panel) {
                    // Tính toán vị trí
                    const sidebarRect = sidebarContainer.getBoundingClientRect();
                    const itemRect = item.getBoundingClientRect();
                    
                    // Vị trí top: Căn theo mục li được hover
                    panel.style.top = itemRect.top - sidebarRect.top + 'px'; 
                    // Vị trí left: Nằm ngay bên phải sidebar
                    panel.style.left = sidebarContainer.clientWidth + 'px'; 

                    panel.classList.add('active');
                }
            });
        });

        // Thêm sự kiện 'mouseleave' cho toàn bộ cột bên trái
        // (bao gồm sidebar VÀ khu vực chứa megamenu)
        sidebarContainer.addEventListener('mouseleave', function() {
            // Đặt một khoảng trễ nhỏ (ví dụ 300ms) trước khi ẩn
            // để người dùng có thời gian di chuột từ sidebar sang megamenu
            hoverTimeout = setTimeout(hideAllMegaMenus, 300);
        });

        // Nếu chuột đi vào lại container (sidebar hoặc panel) thì hủy hẹn giờ
        sidebarContainer.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
        });
        
        // Cần lắng nghe cả các panel nữa
        megamenuPanels.forEach(panel => {
           panel.addEventListener('mouseenter', function() {
               clearTimeout(hoverTimeout);
           });
        });
    }

    function hideAllMegaMenus() {
        megamenuItems.forEach(item => item.classList.remove('megamenu-hover'));
        megamenuPanels.forEach(panel => panel.classList.remove('active'));
    }

});
</script>