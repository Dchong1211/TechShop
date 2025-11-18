<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Test Sản Phẩm</title>
<style>
    body { font-family: Arial; padding: 20px; background: #f4f4f4; }
    button { padding: 10px; margin: 5px 0; width: 100%; cursor: pointer; }
    input { width: 100%; padding: 8px; margin: 5px 0; }
    #result, #auth { background: #000; color: #0f0; padding: 10px; min-height: 40px; white-space: pre-wrap; }
</style>
</head>
<body>

<h1>🛒 TEST PRODUCT API</h1>

<h2>Đăng nhập (để test quyền admin)</h2>

<input id="email" placeholder="Email">
<input id="password" placeholder="Password">
<input type="hidden" id="csrf" value="<?= $csrf ?>">

<button onclick="login()">Đăng nhập</button>
<button onclick="logout()">Đăng xuất</button>

<div id="auth">Chưa check...</div>
<hr>

<h2>PRODUCT PUBLIC API</h2>

<button onclick="api('/api/products')">GET danh sách sản phẩm</button>

<input id="detail_id" placeholder="Product ID">
<button onclick="api('/api/products/' + detail_id.value)">GET chi tiết sản phẩm</button>

<hr>

<h2>PRODUCT ADMIN API</h2>

<input id="p_name" placeholder="Tên">
<input id="p_desc" placeholder="Mô tả">
<input id="p_price" placeholder="Giá">
<input id="p_img" placeholder="Ảnh">
<input id="p_cate" placeholder="Category ID">

<button onclick="addProduct()">POST /admin/products/add</button>

<input id="u_id" placeholder="ID cần sửa">
<button onclick="updateProduct()">POST /admin/products/update</button>

<input id="d_id" placeholder="ID cần xóa">
<button onclick="deleteProduct()">POST /admin/products/delete</button>

<div id="result"></div>

<script>
async function login() {
    let res = await fetch("/TechShop/public/login", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `email=${email.value}&password=${password.value}&csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
}

async function logout() {
    let res = await fetch("/TechShop/public/logout", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
}

async function api(url) {
    let res = await fetch("/TechShop/public" + url);
    result.textContent = await res.text();
}

async function addProduct() {
    let body = new URLSearchParams({
        name: p_name.value,
        description: p_desc.value,
        price: p_price.value,
        image: p_img.value,
        category_id: p_cate.value,
        csrf: csrf.value
    });

    let res = await fetch("/TechShop/public/admin/products/add", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });

    result.textContent = await res.text();
}

async function updateProduct() {
    let body = new URLSearchParams({
        id: u_id.value,
        name: p_name.value,
        description: p_desc.value,
        price: p_price.value,
        image: p_img.value,
        category_id: p_cate.value,
        csrf: csrf.value
    });

    let res = await fetch("/TechShop/public/admin/products/update", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });

    result.textContent = await res.text();
}

async function deleteProduct() {
    let res = await fetch("/TechShop/public/admin/products/delete", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${d_id.value}&csrf=${csrf.value}`
    });

    result.textContent = await res.text();
}
</script>

</body>
</html>
