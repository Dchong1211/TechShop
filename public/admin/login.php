<?php
<<<<<<< HEAD
session_start();
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf_token = CSRF::token();
=======
require_once __DIR__ . '/../../app/helpers/CSRF.php';
$csrf = CSRF::token();
>>>>>>> c7d5161 (Push by Dchong1211 on 23/11/25 23:45:11.62)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
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
=======
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="public/assets/css/cssAdmin/login.css"> 
>>>>>>> c7d5161 (Push by Dchong1211 on 23/11/25 23:45:11.62)
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
<<<<<<< HEAD
                
                <div id="msg-box" class="message-box"></div>

                <form id="form-login">
                    <input type="hidden" name="csrf" value="<?= $csrf_token ?>"> 

=======
                <form action="/TechShop/public/login" method="POST">
>>>>>>> c7d5161 (Push by Dchong1211 on 23/11/25 23:45:11.62)
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="example@gmail.com"> 
                    </div>

                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required placeholder="••••••">
                    </div>
<<<<<<< HEAD

=======
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
>>>>>>> c7d5161 (Push by Dchong1211 on 23/11/25 23:45:11.62)
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