<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/forgot_password.css">
</head>
<body>
    <div class="split-page-container">
        <div class="techshop-slogan">
            <h1>TechShop</h1>
        </div>

        <div class="forgot-password-container">
            <div class="forgot-password-box">
                <div class="forgot-password-box">
                    <h2>Quên mật khẩu</h2>
                    <form action="forgot-password.php" method="POST">
                        <div class="input-group">
                            <label for="username">Tên người dùng / Email</label>
                            <input type="text" name="username" id="username" required>
                        </div>

                        <div class="input-group">
                            <label for="text">Mật khẩu mới</label>
                            <input type="text" name="password" id="password" required>
                        </div>

                        <div class="input-group">
                            <label for="text">Nhập lại mật khẩu mới</label>
                            <input type="text" name="password" id="password" required>
                        </div>

                        <button type="submit" class="forgot-password-button">Mật khẩu mới</button>
                    </form>

                    <div class="function">
                        <p class="login"><a href="/public/admin/login.php">Đăng nhập</a></p>
                        <p class="register"><a href="/public/admin/register.php">Đăng kí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>