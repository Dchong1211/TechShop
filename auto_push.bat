@echo off
cd /d C:\xampp\htdocs\TechShop

:: ===== BACKUP SQL TỰ ĐỘNG =====
echo Dang backup database...

:: Đường dẫn mysqldump
set mysqldump_path=C:\xampp\mysql\bin\mysqldump.exe

:: Tên database
set dbname=techshop

:: File output
set backup_file=C:\xampp\htdocs\TechShop\database\techshop.sql

"%mysqldump_path%" -u root %dbname% > "%backup_file%"

echo Backup SQL hoan tat: %backup_file%
echo ===============================

:: ===== AUTO PUSH GIT =====
for /f "delims=" %%a in ('git config user.name') do set username=%%a

set datetime=%date%_%time%

git add .
git commit -m "Auto push by %username% at %datetime%"
git pull origin main --rebase
git push origin main

echo     PUSH COMPLETED!
