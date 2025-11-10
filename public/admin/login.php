<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Admin</title>
    <link rel="stylesheet" href="/public/assets/css/cssAdmin/login.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Đăng Nhập Quản Trị Viên</h2>
            <form action="index.php" method="POST">
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
            
            <p class="forgot-password"><a href="#">Quên mật khẩu?</a></p>
        </div>
    </div>

</body>
</html>