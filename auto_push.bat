@echo off
chcp 65001 >nul

cd /d C:\xampp\htdocs\TechShop

echo ==========================================
echo 🔥 AUTO PUSH V2 – FULL AUTO HEAL MODE 🔥
echo ==========================================
echo.

REM ================================
REM 1️⃣ FIX DETACHED HEAD
REM ================================
for /f "tokens=3" %%a in ('git status ^| findstr /C:"HEAD detached"') do (
    echo ⚠️  Dang o DETACHED HEAD — Dang auto checkout main...
    git checkout main
)

REM ================================
REM 2️⃣ AUTO FIX REBASE STUCK
REM ================================
if exist ".git\rebase-merge" (
    echo ⚠️  Phat hien rebase-merge — Dang auto cleanup...
    git rebase --abort >nul 2>&1
    rmdir /s /q ".git\rebase-merge"
)

REM ================================
REM 3️⃣ EXPORT DATABASE
REM ================================
echo ==== EXPORT DATABASE ====
php export_db.php
echo 🎉 EXPORT SUCCESS!
echo.

REM ================================
REM 4️⃣ GIT ADD + COMMIT
REM ================================
for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo ==== GIT ADD + COMMIT ====
git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

REM ================================
REM 5️⃣ GIT PULL (SAFE MODE)
REM ================================
echo ==== GIT PULL (REBASE) ====
git pull origin main --rebase
IF %ERRORLEVEL% NEQ 0 (
    echo ❌ LOI REBASE! Auto abort...
    git rebase --abort
    echo ❌ Rebase that bai. Hay merge tay roi chay lai.
    pause
    exit /b
)
echo Pull thanh cong!
echo.

REM ================================
REM 6️⃣ GIT PUSH
REM ================================
echo ==== GIT PUSH ====
git push origin main
IF %ERRORLEVEL% NEQ 0 (
    echo ❌ LOI PUSH! Auto abort...
    pause
    exit /b
)
echo Push thanh cong!
echo.

REM ================================
REM 7️⃣ IMPORT DATABASE (FK OFF FULL SESSION)
REM ================================
echo ==== IMPORT DATABASE ====
echo ⚠️  Dang reset database...

REM DROP + CREATE DB
"C:\xampp\mysql\bin\mysql.exe" -u root -e "DROP DATABASE IF EXISTS techshop; CREATE DATABASE techshop;"

REM IMPORT WITH FK DISABLED
"C:\xampp\mysql\bin\mysql.exe" -u root techshop -e "SET FOREIGN_KEY_CHECKS=0;"
"C:\xampp\mysql\bin\mysql.exe" -u root techshop < "database\techshop.sql"
IF %ERRORLEVEL% NEQ 0 (
    echo ❌ LOI SQL IMPORT!
    echo Hay kiem tra conflict trong techshop.sql.
    pause
    exit /b
)
"C:\xampp\mysql\bin\mysql.exe" -u root techshop -e "SET FOREIGN_KEY_CHECKS=1;"

echo Database import thanh cong!
echo.

echo ==========================================
echo 🎉 CODE + DATABASE DA DONG BO HOAN HAO 🎉
echo ==========================================

pause
