@echo off
cd /d C:\xampp\htdocs\TechShop

echo ==== EXPORT DATABASE (FULL LIKE PHPMYADMIN) ====
"C:\xampp\mysql\bin\mysqldump.exe" ^
  --add-drop-table ^
  --add-locks ^
  --create-options ^
  --disable-keys ^
  --extended-insert ^
  --quick ^
  --set-charset ^
  --skip-comments ^
  --no-tablespaces ^
  --order-by-primary ^
  -u root techshop > database\techshop.sql

echo Export FULL SQL OK!
echo.

for /f "delims=" %%a in ('git config user.name') do set username=%%a
set datetime=%date%_%time%

echo ==== GIT ADD + COMMIT ====
git add .
git commit -m "Auto push by %username% at %datetime%"
echo.

echo ==== GIT PULL (REBASE) ====
git pull origin main --rebase
if %ERRORLEVEL% NEQ 0 (
    echo LOI: Conflict! Hay fix conflict roi chay lai script.
    pause
    exit /b
)

echo ==== GIT PUSH ====
git push origin main
echo.

echo ==== IMPORT DATABASE ====
"C:\xampp\mysql\bin\mysql.exe" -u root techshop < database\techshop.sql

echo ==== DONE! FULL SQL SYNCHRONIZED ====
