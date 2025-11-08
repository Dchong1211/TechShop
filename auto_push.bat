:: Bước 1: Di chuyển đến thư mục project
cd /d C:\xampp\htdocs\TechShop

:: Bước 2: Lấy tên người dùng từ cấu hình Git
for /f "delims=" %%a in ('git config user.name') do set username=%%a

:: Bước 3: Thêm toàn bộ file vào git
git add .

:: Bước 4: Commit với thời gian tự động
set datetime=%date% %time%
git commit -m "Push by %username% on %datetime%"

:: Bước 5: Kéo code mới nhất về (tránh lỗi conflict)
:: git pull origin main --rebase

:: Bước 6: Push code lên GitHub
git push origin main

:: Bước 7: Hiển thị thông báo hoàn tất
echo PUSH COMPLETE!
