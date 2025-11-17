<?php
session_start();
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test API Giỏ Hàng - TechShop</title>

    <style>
        body { 
            font-family: Segoe UI, sans-serif; 
            padding: 25px; 
            background: #f6f7fb;
        }
        h1 { color: #333; }
        h2 { margin-top: 30px; color: #444; }
        .box {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        input {
            padding: 6px 8px;
            width: 200px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        button {
            padding: 8px 15px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #357ab8;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 8px;
            color: #00eaff;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>

    <script>
        async function sendAPI(url, method, formId = null) {
            let options = { method: method };

            if (formId !== null) {
                options.body = new FormData(document.getElementById(formId));
            }

            const response = await fetch(url, options);
            const text = await response.text();

            try {
                const json = JSON.parse(text);
                document.getElementById("result").textContent =
                    JSON.stringify(json, null, 4);
            } catch (e) {
                document.getElementById("result").textContent = text;
            }
        }
    </script>
</head>

<body>

<h1>🔥 Test API Giỏ Hàng TechShop</h1>
<p><b>Lưu ý:</b> Bạn phải đăng nhập trước tại <code>/login</code>.</p>

<!-- ======================================= -->
<!-- 🟦 LẤY GIỎ HÀNG -->
<!-- ======================================= -->
<div class="box">
    <h2>🛒 Lấy giỏ hàng</h2>
    <button onclick="sendAPI('/TechShop/public/api/cart', 'GET')">Gửi Request</button>
</div>

<!-- ======================================= -->
<!-- 🟩 THÊM SẢN PHẨM -->
<!-- ======================================= -->
<div class="box">
    <h2>➕ Thêm vào giỏ</h2>

    <form id="addForm">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">

        <label>Product ID:</label><br>
        <input type="number" name="product_id" value="1"><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="1"><br><br>
    </form>

    <button onclick="sendAPI('/TechShop/public/api/cart/add', 'POST', 'addForm')">
        Thêm vào giỏ
    </button>
</div>

<!-- ======================================= -->
<!-- 🟨 UPDATE SỐ LƯỢNG -->
<!-- ======================================= -->
<div class="box">
    <h2>🔄 Cập nhật số lượng</h2>

    <form id="updateForm">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">

        <label>Product ID:</label><br>
        <input type="number" name="product_id" value="1"><br><br>

        <label>Số lượng mới:</label><br>
        <input type="number" name="quantity" value="3"><br><br>
    </form>

    <button onclick="sendAPI('/TechShop/public/api/cart/update', 'POST', 'updateForm')">
        Update
    </button>
</div>

<!-- ======================================= -->
<!-- 🟥 XOÁ 1 SẢN PHẨM -->
<!-- ======================================= -->
<div class="box">
    <h2>❌ Xoá sản phẩm khỏi giỏ</h2>

    <form id="removeForm">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
        <label>Product ID:</label><br>
        <input type="number" name="product_id" value="1">
    </form>

    <br>
    <button onclick="sendAPI('/TechShop/public/api/cart/remove', 'POST', 'removeForm')">Xoá</button>
</div>

<!-- ======================================= -->
<!-- ⚫ XÓA TOÀN BỘ GIỎ -->
<!-- ======================================= -->
<div class="box">
    <h2>🧹 Xóa toàn bộ giỏ</h2>

    <form id="clearForm">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">
    </form>

    <button onclick="sendAPI('/TechShop/public/api/cart/clear', 'POST', 'clearForm')">
        Clear
    </button>
</div>

<!-- ======================================= -->
<!-- 📦 KẾT QUẢ -->
<!-- ======================================= -->
<div class="box">
    <h2>📦 Kết quả API</h2>
    <pre id="result">...</pre>
</div>

</body>
</html>
