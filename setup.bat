@echo off
REM MovieList Laravel 13 - Quick Setup Script for Windows
REM Run this script to setup MovieList completely

echo.
echo 🎬 MovieList Setup Script
echo ========================
echo.

REM Step 1: Check PHP
echo ✓ Checking PHP version...
php -v | findstr /R "^PHP"

echo.
echo Step 1: Installing Dependencies...
php composer.phar install --ignore-platform-reqs
if errorlevel 1 (
    echo Trying with composer command...
    composer install --ignore-platform-reqs
)

echo.
echo Step 2: Setting up Environment...
php artisan key:generate

echo.
echo Step 3: Creating Database...
if not exist database mkdir database
type nul > database\database.sqlite

echo.
echo Step 4: Running Migrations...
php artisan migrate --force

echo.
echo Step 5: Seeding Database (Optional)...
php artisan db:seed

echo.
echo ✅ Setup Selesai!
echo.
echo ⚠️  PENTING: Edit file .env dan isi TMDB API Key:
echo    TMDB_API_KEY=your_api_key_here
echo.
echo 📝 Untuk mendapatkan TMDB API Key:
echo    1. Buka https://www.themoviedb.org/settings/api
echo    2. Daftar/Login ke TMDB
echo    3. Request API Key
echo    4. Copy API Key ke .env
echo.
echo 🚀 Untuk menjalankan aplikasi:
echo    php artisan serve
echo.
echo 💻 Akses aplikasi di:
echo    http://localhost:8000
echo.
echo 🔓 Login dengan akun demo:
echo    Email: demo@movielist.test
echo    Password: password123
echo.
echo 📚 Dokumentasi:
echo    - README.md untuk overview
echo    - SETUP_INSTRUCTIONS.md untuk troubleshooting
echo    - COMPLETION_SUMMARY.md untuk fitur lengkap
echo.
pause
