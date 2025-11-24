<?php
session_start();
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf_token = CSRF::token();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu | TechShop</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_auth.css"> 
    <style>
        .hidden {
            display: none;
        }

        .message-box {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            display: none;
            text-align: center;
        }

        .message-box.error {
            background: #ffebee;
            color: #c62828;
        }

        .message-box.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .input-group input[readonly] {
            background: #f5f5f5;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="split-page-container">
        <div class="techshop-slogan">
            <h1>TechShop</h1>
            <p>Công nghệ trong tầm tay</p>
        </div>

        <div class="forgot-password-container">
            <div class="forgot-password-box">
                <div id="msg-box" class="message-box"></div>

                <div id="step-email">
                    <h2>Quên mật khẩu</h2>
                    <form id="form-send-otp">
                        <input type="hidden" name="csrf" value="<?= $csrf_token ?>">
                        <div class="input-group">
                            <label for="email">Nhập Email đăng ký</label>
                            <input type="email" id="email" name="email" required placeholder="example@gmail.com">
                        </div>
                        <button type="submit" class="forgot-password-button">Gửi mã xác minh</button>
                    </form>
                </div>

                <div id="step-otp" class="hidden">
                    <h2>Xác minh OTP</h2>
                    <p>Mã OTP đã gửi tới email của bạn.</p>
                    <form id="form-verify-otp">
                        <input type="hidden" name="csrf" value="<?= $csrf_token ?>">
                        <input type="hidden" id="otp_email_hidden" name="email">
                        <div class="input-group">
                            <label>Mã OTP</label>
                            <input type="text" name="otp" required placeholder="123456" style="text-align:center; letter-spacing:4px; font-weight:bold;">
                        </div>
                        <button type="submit" class="forgot-password-button">Xác nhận OTP</button>
                    </form>
                </div>

                <div id="step-reset" class="hidden">
                    <h2>Đặt lại mật khẩu</h2>
                    <form id="form-reset-pass">
                        <input type="hidden" name="csrf" value="<?= $csrf_token ?>">
                        <input type="hidden" id="reset_user_id" name="user_id">

                        <div class="input-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" id="new_pass" name="new_password" required>
                        </div>
                        <div class="input-group">
                            <label>Nhập lại mật khẩu</label>
                            <input type="password" id="confirm_pass" name="confirm_password" required>
                        </div>
                        <button type="submit" class="forgot-password-button">Đổi mật khẩu</button>
                    </form>
                </div>

                <div class="function">
                    <p class="login"><a href="public/admin/login.php">Đăng nhập</a></p>
                    <p class="register"><a href="public/admin/register.php">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="public/assets/js/auth_forgot.js"></script>
</body>

</html>