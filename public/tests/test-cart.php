<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Test Giỏ Hàng</title>
<style>
    body { font-family: Arial; padding: 20px; background: #fafafa; }
    h2 { margin-top: 40px; }
    button {
        width: 100%;
        padding: 12px;
        background: royalblue;
        color: white;
        font-weight: bold;
        border: none;
        margin: 10px 0;
        cursor: pointer;
    }
    input { width: 100%; padding: 10px; margin: 5px 0; }
    #result, #authStatus {
        background: #000;
        color: #0f0;
        padding: 10px;
        min-height: 40px;
        margin-top: 10px;
        white-space: pre-wrap;
    }
</style>
</head>

<body>

<h1>🛒 TEST GIỎ HÀNG (THEO PHÂN QUYỀN)</h1>

<!-- ========================== -->
<!-- LOGIN / LOGOUT -->
<!-- ========================== -->

<h2>Đăng nhập để test quyền</h2>

<input type="email" id="email" placeholder="Email">
<input type="password" id="password" placeholder="Password">
<input type="hidden" id="csrf" value="<?= $csrf ?>">

<button onclick="login()">Đăng nhập</button>
<button onclick="logout()">Đăng xuất</button>

<div id="authStatus">Chưa kiểm tra...</div>

<hr>

<h2>Test API Giỏ hàng</h2>

<!-- GET CART -->
<button onclick="api('/api/cart', 'GET')">GET /api/cart</button>

<input id="add_product" placeholder="Product ID">
<input id="add_qty" placeholder="Số lượng">

<button onclick="addCart()">POST /api/cart/add</button>

<input id="update_product" placeholder="Product ID">
<input id="update_qty" placeholder="Số lượng mới">

<button onclick="updateCart()">POST /api/cart/update</button>

<input id="remove_product" placeholder="Product ID">

<button onclick="removeCart()">POST /api/cart/remove</button>

<button onclick="clearCart()">POST /api/cart/clear</button>

<div id="result"></div>

<script>
async function login() {
    let res = await fetch("/TechShop/public/login", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${email.value}&password=${password.value}&csrf=${csrf.value}`
    });
    let json = await res.text();
    authStatus.textContent = json;
}

async function logout() {
    let res = await fetch("/TechShop/public/logout", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `csrf=${csrf.value}`
    });
    authStatus.textContent = await res.text();
}

async function api(url, method) {
    let res = await fetch("/TechShop/public" + url);
    result.textContent = await res.text();
}

async function addCart() {
    let res = await fetch("/TechShop/public/api/cart/add", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${add_product.value}&quantity=${add_qty.value}&csrf=${csrf.value}`
    });
    result.textContent = await res.text();
}

async function updateCart() {
    let res = await fetch("/TechShop/public/api/cart/update", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${update_product.value}&quantity=${update_qty.value}&csrf=${csrf.value}`
    });
    result.textContent = await res.text();
}

async function removeCart() {
    let res = await fetch("/TechShop/public/api/cart/remove", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${remove_product.value}&csrf=${csrf.value}`
    });
    result.textContent = await res.text();
}

async function clearCart() {
    let res = await fetch("/TechShop/public/api/cart/clear", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `csrf=${csrf.value}`
    });
    result.textContent = await res.text();
}
</script>

</body>
</html>
