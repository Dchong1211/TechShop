@echo off
cd /d C:\xampp\htdocs\TechShop

echo ==== EXPORT DATABASE ====
php export_db.php
echo Export thanh cong!
echo.

:: Lấy username Git
for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo ==== GIT ADD + COMMIT ====
git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo ==== GIT PULL (REBASE) ====
git pull origin main --rebase
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Dang co merge conflict hoac rebase that bai!
    echo Hay sua conflict roi chay lai script nay.
    pause
    exit /b
)
echo Pull thanh cong!
echo.

echo ==== GIT PUSH ====
git push origin main
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Push that bai! Branch dang bi behind hoac conflict.
    pause
    exit /b
)
echo Push thanh cong!
echo.

echo ==== IMPORT DATABASE ====
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SET FOREIGN_KEY_CHECKS=0;" techshop
"C:\xampp\mysql\bin\mysql.exe" -u root techshop < "database\techshop.sql"
IF %ERRORLEVEL% NEQ 0 (
    echo LOI SQL: File techshop.sql bi loi, khong the import!
    echo Vui long kiem tra xem co dong ^<^<^<^<^<^< HEAD khong!
    pause
    exit /b
)
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo ==== DONE ====
echo DATABASE + CODE DA DONG BO THANH CONG!
