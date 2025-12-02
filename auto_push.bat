@echo off
cd /d C:\xampp\htdocs\TechShop

echo ==== EXPORT DATABASE ====
php export_db.php
echo EXPORT DATABASE THANH CONG!
echo.

echo ==== GIT ADD + COMMIT ====
for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo ==== GIT PULL (REBASE) ====
git pull origin main --rebase
echo Pull thanh cong!
echo.

echo ==== GIT PUSH ====
git push origin main
echo Push thanh cong!
echo.

echo ==== IMPORT DATABASE ====

"C:\xampp\mysql\bin\mysql.exe" -u root -e "SET FOREIGN_KEY_CHECKS=0;" techshop
"C:\xampp\mysql\bin\mysql.exe" -u root techshop < "database\techshop.sql"
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo IMPORT DATABASE THANH CONG!
