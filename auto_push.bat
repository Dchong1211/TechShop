@echo off
cd /d C:\xampp\htdocs\TechShop

:: === BACKUP DATABASE ===
echo Dang export database voi PHP...

php export_db.php

echo Export thanh cong!
echo -------------------------

for /f "delims=" %%a in ('git config user.name') do set username=%%a

set datetime=%date%_%time%

git add .
git commit -m "Auto push by %username% at %datetime%"
git pull origin main --rebase
git push origin main

echo PUSH COMPLETED!
pause
