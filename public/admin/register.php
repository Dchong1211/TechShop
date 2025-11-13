<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Kí</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/register.css"> 
</head>
<body>

    <div class="split-page-container">
        
        <div class="techshop-slogan">
            <h1>TechShop</h1>
        </div>

        <div class="register-container">
            <div class="register-box">
                <h2>Đăng kí</h2>
                <form action="register_handler.php" method="POST"> 
                    
                    <div class="input-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" required> 
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Nhập lại mật khẩu</label>
                        <input type="password" id="confirm_password" name="confirm_password" required> 
                    </div>

                    <button type="submit" class="register-button">Đăng Ký</button>

                    <div class="function">
                        <p class="login"><a href="/public/admin/login.php">Đăng nhập</a></p>
                        <p class="forgot-password"><a href="/public/admin/forgot_password.php">Quên mật khẩu?</a></p>
                    </div>

                </form>
            </div>
        </div>
        
    </div>

</body>
</html>