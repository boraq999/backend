@echo off
chcp 65001 >nul
echo ========================================
echo   Database Status Check
echo   Taqnia Distribution Manager v4.0
echo ========================================
echo.

echo 🔍 Checking database connection...
php artisan migrate:status
if %errorlevel% neq 0 (
    echo ❌ Database connection failed!
    echo Please check your .env configuration.
    pause
    exit /b 1
)

echo.
echo 📊 Checking tables and data...
php artisan tinker --execute="
echo 'Users count: ' . App\Models\User::count();
echo 'Products count: ' . DB::table('products')->count();
echo 'Stores count: ' . DB::table('stores')->count();
echo 'Main stock entries: ' . DB::table('main_stock')->count();
echo 'Total tables: ' . count(DB::select('SHOW TABLES'));
"

echo.
echo ✅ Database status check completed!
pause