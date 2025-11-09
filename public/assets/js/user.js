const API_PRODUCTS = '/TechShop/app/api/get_products.php';

document.addEventListener('DOMContentLoaded', async () => {
  const allProducts = await fetchProducts();
  renderByCategory(allProducts, 'pc', 'pc-hot', 5);
  renderByCategory(allProducts, 'laptop', 'laptop-hot', 5);
  renderByCategory(allProducts, 'gear', 'gear-hot', 5);
});

async function fetchProducts() {
  try {
    const res = await fetch(API_PRODUCTS);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    return Array.isArray(data) ? data : [];
  } catch (err) {
    console.error('Lỗi tải sản phẩm:', err);
    return [];
  }
}

function renderByCategory(all, cateSlug, containerId, limit = 6) {
  const container = document.getElementById(containerId);
  if (!container) return;
  const list = all.filter(p => matchCategory(p, cateSlug)).slice(0, limit);
  if (list.length === 0) {
    container.innerHTML = '<p>Không có sản phẩm.</p>';
    return;
  }
  container.innerHTML = '';
  list.forEach(item => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <img src="${item.image || 'https://via.placeholder.com/240x140'}" alt="">
      <h3>${item.name}</h3>
      <p class="price">${formatPrice(item.price)}đ</p>
      <a href="/public/user/product_detail.php?id=${item.id}">Xem chi tiết</a>
    `;
    container.appendChild(card);
  });
}

function matchCategory(product, cateSlug) {
  if (!product.category) return false;
  const c = product.category.toString().toLowerCase();
  return c.includes(cateSlug);
}

function formatPrice(num) {
  if (!num && num !== 0) return '0';
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
