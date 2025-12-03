@echo off
cd /d C:\xampp\htdocs\TechShop

echo =============================================
echo ==== EXPORT DATABASE (phpMyAdmin STYLE) =====
echo =============================================

php export_db.php
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Export database that bai!
    pause
    exit /b
)
echo Export OK!
echo.

for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo =============================================
echo ============= GIT ADD + COMMIT ===============
echo =============================================

git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo =============================================
echo ================= GIT PULL ===================
echo =============================================

git pull origin main --rebase
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Co merge conflict! Hay fix roi chay lai.
    pause
    exit /b
)
echo Pull OK!
echo.

echo =============================================
echo ================= GIT PUSH ===================
echo =============================================

git push origin main
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Push that bai! Thử pull lại hoặc fix conflict.
    pause
    exit /b
)
echo Push OK!
echo.

echo =============================================
echo =============== RESET DATABASE ===============
echo =============================================

C:\xampp\mysql\bin\mysql.exe -u root -e "DROP DATABASE IF EXISTS techshop;"
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE techshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
echo Reset DB OK!
echo.

echo =============================================
echo =============== IMPORT DATABASE ==============
echo =============================================

C:\xampp\mysql\bin\mysql.exe -u root techshop < database\techshop.sql
IF %ERRORLEVEL% NEQ 0 (
    echo LOI IMPORT!!! File SQL bi loi hoac cau truc sai.
    pause
    exit /b
)
echo Import DB OK!
echo.

echo =============================================
echo ======= DONE! CODE + DB DA DONG BO ===========
echo =============================================

pause
