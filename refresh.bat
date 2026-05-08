@echo off
echo ====================================================
echo Thriwex Rent-A-Car: System Refresh Script
echo ====================================================

echo.
echo [1/4] Cleaning Laravel Caches...
cd c:\xampp\htdocs\thriwexRentACar
call php artisan optimize:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan config:clear
call php artisan view:clear

echo.
echo [2/4] Resetting Database and Storage...
call php artisan migrate:fresh --seed
if exist public\storage (
    rmdir /s /q public\storage
)
call php artisan storage:link

echo.
echo [3/4] Clearing Angular Caches & Installing Dependencies...
cd c:\xampp\htdocs\thriwexRentACar\admin-panel
if exist .angular (
    rmdir /s /q .angular
)
call npm install

echo.
echo [4/4] Rebuilding Angular Application...
call npm run build

echo.
echo ====================================================
echo System Refresh Complete!
echo You can now start your Laravel server:
echo    cd c:\xampp\htdocs\thriwexRentACar
echo    php artisan serve
echo.
echo And your Angular server:
echo    cd c:\xampp\htdocs\thriwexRentACar\admin-panel
echo    ng serve
echo ====================================================
pause
