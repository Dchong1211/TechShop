/* ==========================================================================
   FILE: public/assets/js/user.js
   MÔ TẢ: Logic UI cho User (Sidebar, Mega Menu, Live Search, Wishlist)
   PHIÊN BẢN: FINAL FIX – tối ưu & đồng bộ API backend
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ========================================================================
     1. SIDEBAR TOGGLE (Mobile + Desktop)
     ======================================================================== */
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const overlay = document.getElementById('sidebar-overlay');
  const body = document.body;

  if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
          if (window.innerWidth <= 991) {
              body.classList.toggle('sidebar-open');
          } else {
              body.classList.toggle('desktop-sidebar-hidden');
          }
      });
  }

  if (overlay) {
      overlay.addEventListener('click', () => body.classList.remove('sidebar-open'));
  }

  // Mobile submenu accordion
  document.querySelectorAll('.category-sidebar .sidebar-item-with-submenu > a')
      .forEach(toggle => {
          toggle.addEventListener('click', e => {
              if (window.innerWidth <= 991) {
                  e.preventDefault();
                  toggle.parentElement.classList.toggle('submenu-open');
              }
          });
      });


  /* ========================================================================
     2. MEGA MENU (Fix Auto Position)
     ======================================================================== */
  if (window.innerWidth > 991) {
      const items = document.querySelectorAll('.nav-item-has-megamenu');
      const panels = document.querySelectorAll('.sidebar-megamenu-panel');
      const container = document.querySelector('.col-left-sidebar');
      let hideTimeout;

      function hideAll() {
          items.forEach(i => i.classList.remove('megamenu-hover'));
          panels.forEach(p => p.classList.remove('active'));
      }

      if (container) {
          items.forEach(item => {
              const target = item.getAttribute('data-megamenu-target');
              const panel = document.getElementById(target);

              item.addEventListener('mouseenter', () => {
                  clearTimeout(hideTimeout);
                  hideAll();
                  item.classList.add('megamenu-hover');
                  if (panel) panel.classList.add('active');
              });
          });

          container.addEventListener('mouseleave', () => {
              hideTimeout = setTimeout(hideAll, 300);
          });

          container.addEventListener('mouseenter', () => clearTimeout(hideTimeout));

          panels.forEach(panel => {
              panel.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
              panel.addEventListener('mouseleave', () => {
                  hideTimeout = setTimeout(hideAll, 300);
              });
          });
      }
  }


  /* ========================================================================
     3. LIVE SEARCH (API: /TechShop/api/live_search)
     ======================================================================== */
  const searchInput = document.querySelector('input[name="q"]');
  let searchResults = document.getElementById('search-results');

  if (searchInput) {
      if (!searchResults) {
          searchResults = document.createElement('div');
          searchResults.id = 'search-results';
          searchResults.className = 'search-results';
          searchInput.parentElement.style.position = 'relative';
          searchInput.insertAdjacentElement('afterend', searchResults);
      }

      let timeout = null;

      searchInput.addEventListener('input', () => {
          const keyword = searchInput.value.trim();
          clearTimeout(timeout);

          if (keyword.length < 2) {
              searchResults.innerHTML = '';
              searchResults.classList.remove('show');
              return;
          }

          timeout = setTimeout(() => {
              fetch(`/TechShop/api/live_search?q=${encodeURIComponent(keyword)}`)
                  .then(res => res.json())
                  .then(data => {
                      if (!Array.isArray(data) || data.length === 0) {
                          searchResults.innerHTML = `<div class="search-item empty">Không tìm thấy sản phẩm</div>`;
                          searchResults.classList.add('show');
                          return;
                      }

                      let html = "";
                      data.forEach(p => {
                          const price = p.gia_khuyen_mai > 0 ? p.gia_khuyen_mai : p.gia;
                          const fmt = new Intl.NumberFormat('vi-VN').format(price);
                          const img = `/TechShop/public/assets/images/${p.hinh_anh}`;

                          html += `
                              <a href="/TechShop/product/${p.id}" class="search-item">
                                  <img src="${img}" alt="${p.ten_sp}" onerror="this.src='https://via.placeholder.com/60'">
                                  <div class="search-info">
                                      <h4>${p.ten_sp}</h4>
                                      <span class="price">${fmt}₫</span>
                                  </div>
                              </a>`;
                      });

                      searchResults.innerHTML = html;
                      searchResults.classList.add('show');
                  });
          }, 250);
      });

      document.addEventListener('click', e => {
          if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
              searchResults.classList.remove('show');
          }
      });
  }


  /* ========================================================================
     4. WISHLIST (Thả tim)
     ======================================================================== */
  document.body.addEventListener('click', e => {
      const btn = e.target.closest('.btn-wishlist');
      if (!btn) return;

      e.preventDefault();

      const productId = btn.dataset.id;

      // Animation tim
      btn.style.transform = "scale(0.8)";
      setTimeout(() => btn.style.transform = "scale(1)", 200);

      fetch('/TechShop/public/api/toggle_wishlist.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId })
      })
      .then(res => res.json())
      .then(data => {
          if (!data.success) {
              alert(data.message);
              if (data.message.includes('đăng nhập')) {
                  window.location.href = "/TechShop/login";
              }
              return;
          }

          if (data.status === 'added') {
              btn.classList.add('active');
          } else {
              btn.classList.remove('active');

              if (location.href.includes("wishlist.php")) {
                  const card = btn.closest('.product-card');
                  if (card) {
                      card.style.opacity = "0";
                      setTimeout(() => card.remove(), 250);
                  }
              }
          }
      });
  });

});


/* ========================================================================
   UTILITIES
   ======================================================================== */

function formatPrice(num) {
  if (!num && num !== 0) return '0';
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
