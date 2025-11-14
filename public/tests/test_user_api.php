<?php
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
?>

<!DOCTYPE html>
<html>
<body>

<h2>Test Login</h2>

<form action="/TechShop/public/login" method="POST">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Mật khẩu">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Đăng nhập</button>
</form>

<br><hr><br>

<h2>Test Register (Gửi OTP)</h2>

<form action="/TechShop/public/register" method="POST">
    <input type="text" name="name" placeholder="Tên">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Mật khẩu">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Đăng ký</button>
</form>

<br><hr><br>

<h2>Test Xác Minh OTP</h2>

<form action="/TechShop/public/verify-email" method="POST">
    <input type="email" name="email" placeholder="Email đã đăng ký">
    <input type="text" name="otp" placeholder="Nhập mã OTP">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Xác minh</button>
</form>

</body>
</html>
