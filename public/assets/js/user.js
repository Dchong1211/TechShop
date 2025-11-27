/* ==========================================================================
   FILE: public/assets/js/user.js
   ========================================================================== */

const API_PRODUCTS = '/TechShop/public/api/products'; 

document.addEventListener('DOMContentLoaded', async () => {

  // --- XỬ LÝ NÚT DANH MỤC (TOGGLE SIDEBAR) ---
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const overlay = document.getElementById('sidebar-overlay');
  const body = document.body;

  if (toggleBtn) {
    toggleBtn.addEventListener('click', (e) => {
      e.stopPropagation(); 

      // A. Logic cho Mobile (Màn hình nhỏ) -> Hiện Overlay
      if (window.innerWidth <= 991) {
         body.classList.toggle('sidebar-open'); 
      } 
      // B. Logic cho Desktop (Màn hình lớn) -> Ẩn/Hiện Sidebar (Giống Gemini)
      else {
         body.classList.toggle('desktop-sidebar-hidden'); 
      }
    });
  }

  // Đóng sidebar khi click ra ngoài (Chỉ cho Mobile)
  if (overlay) {
    overlay.addEventListener('click', () => {
      body.classList.remove('sidebar-open');
    });
  }

  // Xử lý submenu Mobile
  const submenuToggles = document.querySelectorAll('.category-sidebar .sidebar-item-with-submenu > a');
  submenuToggles.forEach(toggle => {
    toggle.addEventListener('click', (event) => {
      if (window.innerWidth <= 991) {
          event.preventDefault();
          toggle.parentElement.classList.toggle('submenu-open');
      }
    });
  });
});

/* --- CÁC HÀM HỖ TRỢ --- */
async function fetchProducts() { /* ... Giữ nguyên ... */ }
function formatPrice(num) { return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }