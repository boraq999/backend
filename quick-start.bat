@echo off
echo ========================================
echo    Taqnia Distribution Manager Setup
echo ========================================

echo.
echo [1/4] Installing dependencies...
call composer install --no-dev --optimize-autoloader

echo.
echo [2/4] Running migrations...
php artisan migrate:fresh

echo.
echo [3/4] Seeding database...
php artisan db:seed

echo.
echo [4/4] Starting server...
echo.
echo ========================================
echo    Application is ready!
echo    URL: http://localhost:8000
echo.
echo    Test Users:
echo    Admin: admin@test.com / password
echo    Warehouse: warehouse@test.com / password  
echo    Salesman: salesman@test.com / password
echo ========================================
echo.

php artisan serve