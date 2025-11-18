<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();

// Fake danh sách sản phẩm test
$products = [
    ["id" => 1, "name" => "Laptop Asus TUF F15", "price" => 23990000, "image" => "asus_tuf_f15.jpg"],
    ["id" => 2, "name" => "Laptop Acer Nitro 5", "price" => 19990000, "image" => "acer_nitro_5.jpg"],
    ["id" => 3, "name" => "Logitech G Pro X", "price" => 2190000, "image" => "logitech_gprox.jpg"],
    ["id" => 4, "name" => "Razer DeathAdder V2", "price" => 1590000, "image" => "razer_da_v2.jpg"],
    ["id" => 5, "name" => "Keychron K8", "price" => 2690000, "image" => "keychron_k8.jpg"],
    ["id" => 6, "name" => "Akko 3068B", "price" => 1890000, "image" => "akko_3068b.jpg"],
    ["id" => 7, "name" => "Razer BlackShark V2", "price" => 2990000, "image" => "razer_blackshark.jpg"],
    ["id" => 8, "name" => "Logitech G733", "price" => 3490000, "image" => "logitech_g733.jpg"],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Test Giỏ Hàng Full</title>

<style>
    body { font-family: Arial; padding: 20px; background: #f3f4f6; }
    h1 { color: #111; }

    .box {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    input, button {
        padding: 10px;
        margin: 6px 0;
        width: 100%;
        box-sizing: border-box;
    }

    button {
        background: #2563eb;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border-radius: 6px;
    }

    button.red { background: #dc2626; }
    button.orange { background: #f97316; }

    .log {
        white-space: pre-line;
        background: #111;
        color: #0f0;
        padding: 10px;
        border-radius: 6px;
        margin-top: 10px;
        min-height: 50px;
    }

    /* Sản phẩm */
    .product {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        align-items: center;
    }

    .product img {
        width: 60px;
        height: 60px;
        border-radius: 4px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #ddd;
    }

    .cart-item img {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        margin-right: 15px;
    }

    .qty-box {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .qty-btn {
        width: 28px; 
        height: 28px; 
        border: none;
        background: #2563eb;
        color: white;
        font-size: 18px;
        border-radius: 4px;
        cursor: pointer;
    }

    .remove-btn {
        color: red;
        cursor: pointer;
        margin-left: 10px;
        font-size: 20px;
    }
</style>
</head>
<body>

<h1>🛒 TEST GIỎ HÀNG FULL (UI + THÊM + UPDATE + DELETE)</h1>

<!-- ========================== -->
<!-- LOGIN / LOGOUT -->
<!-- ========================== -->

<div class="box">
    <h2>Đăng nhập</h2>

    <input type="email" id="email" placeholder="Email">
    <input type="password" id="password" placeholder="Password">
    <input type="hidden" id="csrf" value="<?= $csrf ?>">

    <button onclick="login()">Đăng nhập</button>
    <button class="red" onclick="logout()">Đăng xuất</button>

    <div id="auth" class="log">Chưa đăng nhập</div>
</div>

<!-- ========================== -->
<!-- DANH SÁCH SẢN PHẨM ĐỂ THÊM -->
<!-- ========================== -->

<div class="box">
    <h2>Danh sách sản phẩm (click Thêm để add vào giỏ)</h2>

    <?php foreach ($products as $p): ?>
        <div class="product">
            <img src="/TechShop/public/assets/images/<?= $p['image'] ?>">
            <div style="flex:1">
                <b><?= $p['name'] ?></b><br>
                Giá: <?= number_format($p['price']) ?>đ
            </div>
            <button onclick="addCart(<?= $p['id'] ?>)">Thêm</button>
        </div>
    <?php endforeach; ?>
</div>

<!-- ========================== -->
<!-- CART LIST -->
<!-- ========================== -->

<div class="box">
    <h2>Giỏ hàng</h2>
    <div id="cart">Loading...</div>

    <button class="orange" onclick="clearCart()">Xóa toàn bộ giỏ</button>
</div>

<div id="log" class="log"></div>

<script>
async function login() {
    let res = await fetch("/TechShop/public/login", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `email=${email.value}&password=${password.value}&csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
    loadCart();
}

async function logout() {
    let res = await fetch("/TechShop/public/logout", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `csrf=${csrf.value}`
    });
    auth.textContent = await res.text();
    cart.innerHTML = "Hãy đăng nhập";
}

async function loadCart() {
    let res = await fetch("/TechShop/public/api/cart");
    let json = await res.json();

    if (!json.success) {
        cart.textContent = json.message;
        return;
    }

    renderCart(json.cart);
}

function renderCart(items) {
    if (items.length === 0) {
        cart.innerHTML = "<i>Giỏ trống</i>";
        return;
    }

    cart.innerHTML = items.map(item => `
        <div class="cart-item">
            <img src="/TechShop/public/assets/images/${item.image}">
            <div style="flex:1">
                <b>${item.name}</b><br>
                Giá: ${item.price.toLocaleString()}đ
            </div>

            <div class="qty-box">
                <button class="qty-btn" onclick="updateQty(${item.cart_id}, ${item.quantity - 1})">−</button>
                <b>${item.quantity}</b>
                <button class="qty-btn" onclick="updateQty(${item.cart_id}, ${item.quantity + 1})">＋</button>
            </div>

            <span class="remove-btn" onclick="removeCart(${item.cart_id})">×</span>
        </div>
    `).join("");
}

async function addCart(pid) {
    let res = await fetch("/TechShop/public/api/cart/add", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `product_id=${pid}&quantity=1&csrf=${csrf.value}`
    });

    log.textContent = await res.text();
    loadCart();
}

async function updateQty(cart_id, qty) {
    if (qty < 1) return;

    let res = await fetch("/TechShop/public/api/cart/update", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `cart_id=${cart_id}&quantity=${qty}&csrf=${csrf.value}`
    });

    log.textContent = await res.text();
    loadCart();
}

async function removeCart(cart_id) {
    let res = await fetch("/TechShop/public/api/cart/remove", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `cart_id=${cart_id}&csrf=${csrf.value}`
    });

    log.textContent = await res.text();
    loadCart();
}

async function clearCart() {
    let res = await fetch("/TechShop/public/api/cart/clear", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `csrf=${csrf.value}`
    });

    log.textContent = await res.text();
    loadCart();
}

loadCart();
</script>

</body>
</html>
