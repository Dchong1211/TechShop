@echo off
echo ==============================
echo 🚀 AUTO PUSH TO GITHUB START
echo ==============================

:: Bước 1: Di chuyển đến thư mục project (sửa lại nếu khác)
cd /d C:\xampp\htdocs\TechShop

:: Bước 2: Thêm toàn bộ file vào git
git add .

:: Bước 3: Commit với thời gian tự động
set datetime=%date% %time%
git commit -m "Auto push on %datetime%"

:: Bước 4: Kéo code mới nhất về (tránh lỗi conflict)
git pull origin main --rebase

:: Bước 5: Push code lên GitHub
git push origin main

echo ==============================
echo ✅ PUSH SUCCESSFULLY!
echo ==============================
pause
