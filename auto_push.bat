@echo off
cd /d C:\xampp\htdocs\TechShop

echo ==== EXPORT DATABASE ====
C:\xampp\php\php.exe export_db.php
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Export database that bai!
    pause
    exit /b
)
echo Export thanh cong!
echo.

:: Lay username Git
for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo ==== GIT ADD + COMMIT ====
git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo ==== GIT PULL (REBASE) ====
git pull origin main --rebase --autostash
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Dang co merge conflict hoac rebase that bai!
    echo Hay sua conflict thu cong roi chay lai script.
    pause
    exit /b
)
echo Pull thanh cong!
echo.

echo ==== GIT PUSH ====
git push origin main
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Push that bai!
    pause
    exit /b
)
echo Push thanh cong!
echo.

echo ==== IMPORT DATABASE ====

C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS techshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=0;" techshop

C:\xampp\mysql\bin\mysql.exe -u root techshop < "database\techshop.sql"
IF %ERRORLEVEL% NEQ 0 (
    echo LOI SQL: File techshop.sql thieu du lieu hoac co conflict!
    echo Vui long kiem tra dong ^<^<^<^<^<^< HEAD trong file.
    pause
    exit /b
)

C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo ==== DONE ====
echo DATABASE + CODE DA DONG BO THANH CONG!
pause
