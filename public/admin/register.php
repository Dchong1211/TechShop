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
    <title>Đăng Ký | TechShop</title>
    <link rel="stylesheet" href="../assets/css/cssAdmin/admin_auth.css"> 
    <style>
        .hidden {
            display: none;
        }

        .message-box {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-size: 0.9rem;
            display: none;
            text-align: center;
        }

        .message-box.error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .message-box.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
            cursor: wait;
        }
    </style>
</head>
<body>
    <div class="split-page-container">
        <div class="techshop-slogan">
            <h1>TechShop</h1>
            <p>Công nghệ trong tầm tay</p>
        </div>

        <div class="register-container">
            <div class="register-box">
                <div id="message-box" class="message-box"></div>

                <div id="register-step">
                    <h2>Đăng Ký</h2>
                    <form id="form-register">
                        <input type="hidden" name="csrf" value="<?= $csrf_token ?>">

                        <div class="input-group">
                            <label for="name">Họ và Tên</label>
                            <input type="text" id="name" name="name" required placeholder="Nhập họ tên hiển thị">
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required placeholder="example@email.com">
                        </div>

                        <div class="input-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="input-group">
                            <label for="confirm_password">Nhập lại mật khẩu</label>
                            <input type="password" id="confirm_password" required>
                        </div>

                        <button type="submit" class="register-button">Đăng Ký</button>

                        <div class="function">
                            <p class="login">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                        </div>
                    </form>
                </div>

                <div id="otp-step" class="hidden">
                    <h2>Xác thực Email</h2>
                    <p style="text-align: center; font-size: 0.9rem; color: #666;">
                        Mã OTP đã được gửi đến <strong id="otp-email-display"></strong>
                    </p>

                    <form id="form-verify">
                        <input type="hidden" name="csrf" value="<?= $csrf_token ?>">
                        <input type="hidden" id="verify_email" name="email">

                        <div class="input-group">
                            <label for="otp">Nhập mã OTP</label>
                            <input type="text" id="otp" name="otp" required placeholder="123456" maxlength="6" style="text-align: center; letter-spacing: 5px; font-weight: bold; font-size: 1.5rem;">
                        </div>

                        <button type="submit" class="register-button">Xác Minh</button>

                        <div class="function">
                            <p class="forgot-password"><a href="#" onclick="location.reload()">Quay lại đăng ký</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="public/assets/js/auth_register.js"></script>
</body>
</html>