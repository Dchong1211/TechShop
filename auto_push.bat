@echo off
cd /d C:\xampp\htdocs\TechShop

echo ==== EXPORT DATABASE (CLEAN DUMP) ====
"C:\xampp\mysql\bin\mysqldump.exe" ^
  --skip-comments ^
  --skip-add-drop-table ^
  --no-tablespaces ^
  --order-by-primary ^
  --compact ^
  --extended-insert=FALSE ^
  -u root techshop > database\techshop.sql

echo Export OK!
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
    echo LOI: Co conflict! Hay sua conflict roi chay lai.
    pause
    exit /b
)

echo ==== GIT PUSH ====
git push origin main
echo.

echo ==== IMPORT DATABASE ====
"C:\xampp\mysql\bin\mysql.exe" -u root techshop < database\techshop.sql

echo ==== DONE! ====
