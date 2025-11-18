<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Test Products API</title>
<style>
    body { 
        font-family: Arial; 
        padding: 20px; 
        background: #f4f4f4; 
        line-height: 1.6;
    }
    h1 { margin-bottom: 10px; }
    h2 { margin-top: 30px; }
    input, button {
        padding: 10px;
        margin: 5px 0;
        width: 100%;
        box-sizing: border-box;
    }
    button {
        cursor: pointer;
        background: #007bff;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 5px;
    }
    button:hover { opacity: 0.9; }

    #auth, #result {
        background: #111;
        color: #0f0;
        padding: 12px;
        white-space: pre-wrap;
        min-height: 40px;
        border-radius: 4px;
        margin-top: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background: white;
    }
    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    table th {
        background: #007bff;
        color: white;
    }
</style>
</head>

<body>

<h1>🛍 TEST PRODUCT API</h1>

<!-- ===================================== -->
<!-- LOGIN / LOGOUT -->
<!-- ===================================== -->
<h2>Đăng nhập để test quyền Admin/User</h2>

<input id="email" placeholder="Email (VD: techshopNT@gmail.com)">
<input id="password" placeholder="Password (VD: Admin@123)">
<input type="hidden" id="csrf" value="<?= $csrf ?>">

<button onclick="login()">Đăng nhập</button>
<button onclick="logout()">Đăng xuất</button>

<div id="auth">Chưa check...</div>

<hr>

<!-- ===================================== -->
<!-- LIST + DETAIL -->
<!-- ===================================== -->
<h2>Sản phẩm (Tự load khi mở trang)</h2>

<button onclick="loadProducts()">GET /api/products</button>

<input id="detail_id" placeholder="Nhập Product ID để xem chi tiết">
<button onclick="api('/api/products/' + detail_id.value)">GET /api/products/{id}</button>

<div id="result"></div>

<hr>

<!-- ===================================== -->
<!-- ADMIN CRUD -->
<!-- ===================================== -->
<h2>Admin CRUD Product</h2>

<h3>Thêm sản phẩm</h3>
<input id="p_name" placeholder="Tên sản phẩm">
<input id="p_desc" placeholder="Mô tả">
<input id="p_price" placeholder="Giá (VD: 2990000)">
<input id="p_img" placeholder="Tên ảnh (VD: laptop.jpg)">
<input id="p_cate" placeholder="Category ID (VD: 1)">
<button onclick="addProduct()">POST /admin/products/add</button>

<h3>Cập nhật sản phẩm</h3>
<input id="u_id" placeholder="ID sản phẩm cần sửa">
<button onclick="updateProduct()">POST /admin/products/update</button>

<h3>Xóa sản phẩm</h3>
<input id="d_id" placeholder="ID sản phẩm cần xóa">
<button onclick="deleteProduct()">POST /admin/products/delete</button>


<script>
/* AUTO LOAD PRODUCTS */
window.onload = function () {
    loadProducts();
}

/* Fetch danh sách sản phẩm */
async function loadProducts() {
    let res = await fetch("/TechShop/public/api/products");
    let txt = await res.text();
    showResult(txt);
}

/* LOGIN */
async function login() {
    let res = await fetch("/TechShop/public/login", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `email=${email.value}&password=${password.value}&csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
}

/* LOGOUT */
async function logout() {
    let res = await fetch("/TechShop/public/logout", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
}

/* GỌI API TÙY Ý */
async function api(url) {
    let res = await fetch("/TechShop/public" + url);
    let txt = await res.text();
    showResult(txt);
}

/* ADMIN: ADD PRODUCT */
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
    showResult(await res.text());
}

/* ADMIN: UPDATE PRODUCT */
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
    showResult(await res.text());
}

/* ADMIN: DELETE PRODUCT */
async function deleteProduct() {
    let res = await fetch("/TechShop/public/admin/products/delete", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${d_id.value}&csrf=${csrf.value}`
    });
    showResult(await res.text());
}

/* HIỂN THỊ JSON + TABLE */
function showResult(txt) {
    try {
        let json = JSON.parse(txt);
        result.textContent = JSON.stringify(json, null, 2);

        if (json.data && Array.isArray(json.data)) {
            renderTable(json.data);
        }
    } catch {
        result.textContent = txt;
    }
}

/* TẠO BẢNG SẢN PHẨM */
function renderTable(items) {
    let html = `
        <table>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Category</th>
            </tr>
    `;

    items.forEach(p => {
        html += `
            <tr>
                <td>${p.id}</td>
                <td>${p.name}</td>
                <td>${p.price}</td>
                <td>${p.image}</td>
                <td>${p.category_id}</td>
            </tr>
        `;
    });

    html += "</table>";
    result.innerHTML = html;
}
</script>

</body>
</html>
