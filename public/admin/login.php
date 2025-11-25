
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_auth.css">
    <?php
        require_once __DIR__ . '/../../app/helpers/CSRF.php';
        $csrf = CSRF::token();
    ?>
</head>

<body>

    <div class="split-page-container">
        
        <div class="techshop-slogan">
            <h1>TechShop</h1>
        </div>

        <div class="login-container">
            <div class="login-box"> 
                <h2>Đăng Nhập</h2>

                <div id="msg-box" class="message-box" style="display:none;"></div>

                <form id="form-login">
                    <div class="input-group">
                        <label for="username">Tên người dùng / Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <input type="hidden" name="csrf" value="<?= $csrf ?>">

                    <button type="submit" class="login-button">Đăng Nhập</button>
                </form>

                <div class="function">
                    <p class="forgot-password">
                        <a href="/TechShop/forgot-password">Quên mật khẩu?</a>
                    </p>
                    <p class="register">
                        <a href="/TechShop/register">Đăng ký</a>
                    </p>
                </div>

            </div>
        </div>
        <script src="/TechShop/public/assets/js/auth_login.js"></script>
    </div>
    
</body>
</html>
