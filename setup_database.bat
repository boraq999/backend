@echo off
echo Starting Taqnia Distribution Manager Database Setup...
echo.

echo Step 1: Running migrations...
php artisan migrate:fresh --force
if %errorlevel% neq 0 (
    echo Error: Migration failed!
    pause
    exit /b 1
)

echo.
echo Step 2: Seeding database with initial data...
php artisan db:seed --class=TaqniaSeeder
if %errorlevel% neq 0 (
    echo Error: Seeding failed!
    pause
    exit /b 1
)

echo.
echo ✅ Database setup completed successfully!
echo.
echo Initial users created:
echo - Admin: username=admin, password=admin123
echo - Warehouse Keeper: username=keeper1, password=keeper123  
echo - Salesman: username=salesman1, password=sales123
echo.
pause