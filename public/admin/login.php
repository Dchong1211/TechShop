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
    <title>Đăng Nhập | TechShop</title>
    <base href="/TechShop/">
    <link rel="stylesheet" href="public/assets/css/cssAdmin/login.css"> 
    <style>
        .message-box {
            display: none;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
            font-size: 0.9rem;
        }

        .message-box.error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .message-box.success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="split-page-container">
        <div class="techshop-slogan">
            <h1>TechShop</h1>
            <p>Công nghệ trong tầm tay</p>
        </div>

        <div class="login-container">
            <div class="login-box"> 
                <h2>Đăng Nhập</h2>
                
                <div id="msg-box" class="message-box"></div>

                <form id="form-login">
                    <input type="hidden" name="csrf" value="<?= $csrf_token ?>"> 

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="example@gmail.com"> 
                    </div>

                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required placeholder="••••••">
                    </div>

                    <button type="submit" class="login-button">Đăng Nhập</button>
                </form>
                                    
                <div class="function">
                    <p class="forgot-password"><a href="public/admin/forgot_password.php">Quên mật khẩu?</a></p>
                    <p class="register"><a href="public/admin/register.php">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="public/assets/js/auth_login.js"></script>
</body>
</html>