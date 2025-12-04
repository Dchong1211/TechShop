/* ==========================================================================
   FILE: public/assets/js/user.js
   MÔ TẢ: Logic chung cho User (Sidebar, Menu, Search, Wishlist)
   PHIÊN BẢN: Final Fix (Mega Menu cố định vị trí)
   ========================================================================== */

const API_PRODUCTS = '/TechShop/public/api/products'; 

document.addEventListener('DOMContentLoaded', async () => {

  // ============================================================
  // 1. XỬ LÝ SIDEBAR (TOGGLE ẨN/HIỆN)
  // ============================================================
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const overlay = document.getElementById('sidebar-overlay');
  const body = document.body;

  if (toggleBtn) {
    toggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      // Mobile: Hiện sidebar đè lên
      if (window.innerWidth <= 991) {
         body.classList.toggle('sidebar-open');
      } 
      // Desktop: Ẩn sidebar đi
      else {
         body.classList.toggle('desktop-sidebar-hidden');
      }
    });
  }

  if (overlay) {
    overlay.addEventListener('click', () => {
      body.classList.remove('sidebar-open');
    });
  }

  // Accordion cho Mobile Submenu
  const submenuToggles = document.querySelectorAll('.category-sidebar .sidebar-item-with-submenu > a');
  submenuToggles.forEach(toggle => {
    toggle.addEventListener('click', (event) => {
      if (window.innerWidth <= 991) {
          event.preventDefault();
          toggle.parentElement.classList.toggle('submenu-open');
      }
    });
  });


  // ============================================================
  // 2. XỬ LÝ MEGA MENU (FIX LỖI NHẢY VỊ TRÍ)
  // ============================================================
  if (window.innerWidth > 991) {
      const megamenuItems = document.querySelectorAll('.nav-item-has-megamenu');
      const megamenuPanels = document.querySelectorAll('.sidebar-megamenu-panel');
      const sidebarContainer = document.querySelector('.col-left-sidebar');
      let hoverTimeout;

      // Hàm tắt hết menu
      function hideAllMegaMenus() {
          megamenuItems.forEach(item => item.classList.remove('megamenu-hover'));
          megamenuPanels.forEach(panel => panel.classList.remove('active'));
      }

      if (sidebarContainer) {
          megamenuItems.forEach(item => {
              const targetId = item.getAttribute('data-megamenu-target');
              const panel = document.getElementById(targetId);

              item.addEventListener('mouseenter', function() {
                  clearTimeout(hoverTimeout);
                  hideAllMegaMenus();
                  
                  item.classList.add('megamenu-hover');
                  if (panel) {
                      // --- QUAN TRỌNG: ĐÃ XÓA ĐOẠN TÍNH TOÁN TOP ---
                      // Menu sẽ tự động ăn theo CSS (top: 0) -> Luôn nằm ở đầu
                      
                      panel.classList.add('active');
                  }
              });
          });

          // Khi chuột rời khỏi khu vực Sidebar -> Chờ 300ms mới tắt
          sidebarContainer.addEventListener('mouseleave', function() {
              hoverTimeout = setTimeout(hideAllMegaMenus, 300);
          });

          // Nếu chuột quay lại Sidebar -> Hủy lệnh tắt
          sidebarContainer.addEventListener('mouseenter', function() {
              clearTimeout(hoverTimeout);
          });
          
          // Nếu chuột đi vào Panel Menu con -> Hủy lệnh tắt
          megamenuPanels.forEach(panel => {
             panel.addEventListener('mouseenter', () => clearTimeout(hoverTimeout));
             panel.addEventListener('mouseleave', () => {
                 hoverTimeout = setTimeout(hideAllMegaMenus, 300);
             });
          });
      }
  }


  // ============================================================
  // 3. TÌM KIẾM TỨC THÌ (LIVE SEARCH)
  // ============================================================
  const searchInput = document.querySelector('input[name="q"]');
  let searchResults = document.getElementById('search-results');
  if (!searchResults && searchInput) {
      searchResults = document.createElement('div');
      searchResults.id = 'search-results';
      searchResults.className = 'search-results';
      if(searchInput.parentElement) searchInput.parentElement.style.position = 'relative'; 
      searchInput.insertAdjacentElement('afterend', searchResults);
  }

  let searchTimeout = null;

  if (searchInput) {
      searchInput.addEventListener('input', function() {
          const keyword = this.value.trim();
          clearTimeout(searchTimeout);

          if (keyword.length < 2) {
              searchResults.classList.remove('show');
              searchResults.innerHTML = '';
              return;
          }

          searchTimeout = setTimeout(() => {
              fetch(`/TechShop/api/live_search?q=${keyword}`)
                  .then(res => res.json())
                  .then(data => {
                      if (data.length > 0) {
                          let html = '';
                          data.forEach(p => {
                              let price = parseFloat(p.gia_khuyen_mai) > 0 ? p.gia_khuyen_mai : p.gia;
                              let priceFmt = new Intl.NumberFormat('vi-VN').format(price);
                              let imgUrl = `public/assets/images/${p.hinh_anh}`;
                              
                              html += `
                                  <a href="public/user/product_detail.php?id=${p.id}" class="search-item">
                                      <img src="${imgUrl}" alt="${p.ten_sp}" onerror="this.src='https://via.placeholder.com/50'">
                                      <div class="search-info">
                                          <h4>${p.ten_sp}</h4>
                                          <span class="price">${priceFmt}₫</span>
                                      </div>
                                  </a>
                              `;
                          });
                          searchResults.innerHTML = html;
                          searchResults.classList.add('show');
                      } else {
                          searchResults.innerHTML = '<div class="search-item" style="padding:15px; justify-content:center;">Không tìm thấy sản phẩm</div>';
                          searchResults.classList.add('show');
                      }
                  })
                  .catch(err => console.error(err));
          }, 300);
      });

      document.addEventListener('click', (e) => {
          if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
              searchResults.classList.remove('show');
          }
      });
  }


  // ============================================================
  // 4. XỬ LÝ THẢ TIM (WISHLIST)
  // ============================================================
  document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.btn-wishlist');
      if (btn) {
          e.preventDefault(); 
          e.stopPropagation();

          const productId = btn.dataset.id;
          
          btn.style.transform = "scale(0.8)";
          setTimeout(() => btn.style.transform = "scale(1)", 200);

          fetch('/TechShop/public/api/toggle_wishlist.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ product_id: productId })
          })
          .then(res => res.json())
          .then(data => {
              if (data.success) {
                  if (data.status === 'added') {
                      btn.classList.add('active'); 
                  } else {
                      btn.classList.remove('active'); 
                      if (window.location.href.includes('wishlist.php')) {
                          const card = btn.closest('.product-card');
                          if (card) {
                              card.style.opacity = '0';
                              setTimeout(() => card.remove(), 300);
                          }
                      }
                  }
              } else {
                  alert(data.message); 
                  if(data.message && data.message.includes('đăng nhập')) {
                      window.location.href = '/TechShop/public/admin/login.php';
                  }
              }
          })
          .catch(err => console.error(err));
      }
  });

});

// UTILITIES
async function fetchProducts() {
  try {
    const res = await fetch(API_PRODUCTS);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const response = await res.json();
    if (response.success && Array.isArray(response.data)) {
        return response.data;
    }
    return [];
  } catch (err) {
    console.error('Lỗi tải sản phẩm:', err);
    return [];
  }
}

function formatPrice(num) {
  if (!num && num !== 0) return '0';
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}