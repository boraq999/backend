@echo off
chcp 65001 >nul
echo ========================================
echo   Taqnia Distribution Manager v4.0
echo   Database Setup Script
echo ========================================
echo.

echo 📋 Checking database configuration...
echo Database: taqnia-distribution-manager
echo Host: 127.0.0.1:3306
echo Username: root
echo.

echo ⚠️  Make sure MySQL is running and the database exists!
echo.
set /p continue="Continue with setup? (y/n): "
if /i "%continue%" neq "y" (
    echo Setup cancelled.
    pause
    exit /b 0
)

echo.
echo 🔄 Step 1: Clearing previous migrations...
php artisan migrate:reset --force
if %errorlevel% neq 0 (
    echo ❌ Error: Failed to reset migrations!
    echo Make sure MySQL is running and database exists.
    pause
    exit /b 1
)

echo.
echo 🔄 Step 2: Running fresh migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ❌ Error: Migration failed!
    echo Check your database connection settings.
    pause
    exit /b 1
)

echo.
echo 🌱 Step 3: Seeding database with initial data...
php artisan db:seed --class=TaqniaSeeder --force
if %errorlevel% neq 0 (
    echo ❌ Error: Seeding failed!
    pause
    exit /b 1
)

echo.
echo ✅ Database setup completed successfully!
echo.
echo 👥 Initial users created:
echo ┌─────────────────┬──────────────┬─────────────┐
echo │ Role            │ Username     │ Password    │
echo ├─────────────────┼──────────────┼─────────────┤
echo │ Admin           │ admin        │ admin123    │
echo │ Warehouse Keeper│ keeper1      │ keeper123   │
echo │ Salesman        │ salesman1    │ sales123    │
echo └─────────────────┴──────────────┴─────────────┘
echo.
echo 📦 Sample data created:
echo - 3 Products (منتج أ، منتج ب، منتج ج)
echo - 3 Stores (متجر الشرق، متجر الغرب، متجر الشمال)
echo - Initial stock quantities
echo.
echo 🎯 Total tables created: 26
echo.
echo Ready to start development! 🚀
pause