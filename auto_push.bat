@echo off
cd /d C:\xampp\htdocs\TechShop

:: ========= FIX DEAD REBASE =========
if exist ".git\rebase-merge" (
    echo Found old rebase-merge, deleting...
    rd /s /q ".git\rebase-merge"
)

echo ==== EXPORT DATABASE ====
C:\xampp\php\php.exe export_db.php
IF %ERRORLEVEL% NEQ 0 (
    echo LOI: Export database that bai!
    pause
    exit /b
)
echo Export OK!

:: Check file SQL dung luong > 0
if not exist "database\techshop.sql" (
    echo LOI: Khong tim thay file techshop.sql!
    pause
    exit /b
)

for %%A in ("database\techshop.sql") do set size=%%~zA

if %size% LSS 50 (
    echo LOI: File SQL bi rong hoac export loi! Dung luong: %size% bytes
    pause
    exit /b
)

echo File SQL hop le: %size% bytes
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
    echo LOI: Merge conflict hoac rebase loi!
    pause
    exit /b
)
echo Pull thanh cong!

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
    echo LOI SQL: File techshop.sql bi loi hoac co conflict!
    pause
    exit /b
)

C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo ==== DONE ====
echo SYNC HOAN TAT!
pause
