<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/login.css"> 
</head>
<body>

    <div class="split-page-container">
        
        <div class="techshop-slogan">
            <h1>TechShop</h1>
        </div>

        <div class="login-container">
            <div class="login-box"> 
                <h2>Đăng Nhập</h2>
                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label for="username">Tên người dùng / Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="login-button">Đăng Nhập</button>
                </form>
                
                <div class="function">
                    <p class="forgot-password"><a href="/public/admin/forgot_password.php">Quên mật khẩu?</a></p>
                    <p class="register"><a href="/public/admin/register.php">Đăng kí</a></p>
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>