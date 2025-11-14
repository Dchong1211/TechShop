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

<br><hr><br>

<!-- ========================================================= -->
<!-- 📌 THÊM MỚI: QUÊN MẬT KHẨU BẰNG OTP -->
<!-- ========================================================= -->


<h2>Test Quên Mật Khẩu (Gửi OTP Reset)</h2>

<form action="/TechShop/public/forgot-password" method="POST">
    <input type="email" name="email" placeholder="Nhập email để gửi OTP reset">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Gửi OTP Reset</button>
</form>

<br><hr><br>


<h2>Test Xác Minh OTP Reset</h2>

<form action="/TechShop/public/verify-reset-otp" method="POST">
    <input type="email" name="email" placeholder="Email đã yêu cầu reset">
    <input type="text" name="otp" placeholder="Nhập OTP reset">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Xác minh OTP Reset</button>
</form>

<br><hr><br>


<h2>Test Đặt Lại Mật Khẩu (Sau khi xác minh OTP)</h2>

<form action="/TechShop/public/reset-password-otp" method="POST">
    <input type="number" name="user_id" placeholder="User ID được trả về sau khi verify OTP">
    <input type="password" name="new_password" placeholder="Mật khẩu mới">
    <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới">

    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <button type="submit">Đổi mật khẩu</button>
</form>

</body>
</html>
