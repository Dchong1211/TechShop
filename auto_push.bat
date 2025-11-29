@echo off
cd /d C:\xampp\htdocs\TechShop

:: Format date/time
for /f "tokens=1-3 delims=/ " %%a in ("%date%") do (
    set dd=%%a
    set mm=%%b
    set yy=%%c
)
for /f "tokens=1-3 delims=:." %%h in ("%time%") do (
    set hh=%%h
    set min=%%i
    set sec=%%j
)
set datetime=%yy%-%mm%-%dd%_%hh%-%min%-%sec%

:: Get git username
for /f "delims=" %%a in ('git config user.name') do set username=%%a

:: Export database
php export_db.php
if %errorlevel% neq 0 (
    echo Export DB failed.
    pause
    exit /b
)

:: Git add + commit
git add .
git commit -m "Auto push by %username% at %datetime%"

:: Pull code (no rebase)
git pull origin main

:: Push code
git push origin main
if %errorlevel% neq 0 (
    echo Push failed.
    pause
    exit /b
)

:: Import database
C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=0;" techshop
C:\xampp\mysql\bin\mysql.exe -u root techshop < database\techshop.sql
C:\xampp\mysql\bin\mysql.exe -u root -e "SET FOREIGN_KEY_CHECKS=1;" techshop

echo Done.