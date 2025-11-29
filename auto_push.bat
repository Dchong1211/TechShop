@echo off
cd /d C:\xampp\htdocs\TechShop

echo =======================================
echo      AUTO PUSH + AUTO SYNC DATABASE
echo =======================================

:: ===== EXPORT DB (máy nào có DB thì export) =====
echo Dang export database voi PHP...
php export_db.php
echo Export thanh cong!
echo.

:: ===== GIT ADD / COMMIT / PUSH =====
for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo Dang commit code...
git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo Dang pull code moi nhat...
git pull origin main --rebase
echo.

echo Dang push len GitHub...
git push origin main
echo.

:: ===== IMPORT DB (máy nao khong co DB moi nhat thi import) =====
echo =======================================
echo  DANG DONG BO DATABASE LOCAL
echo =======================================

C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=0;" techshop
C:\xampp\mysql\bin\mysql.exe -u root techshop < database\techshop.sql
C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo =======================================
echo  DATABASE + CODE DA DONG BO THANH CONG
echo =======================================
