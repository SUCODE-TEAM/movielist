#!/usr/bin/env bash
# MovieList Laravel 13 - Quick Setup Script
# Run this script to setup MovieList completely

echo "🎬 MovieList Setup Script"
echo "========================"
echo ""

# Step 1: Check PHP
echo "✓ Checking PHP version..."
php -v | head -1

echo ""
echo "Step 1: Installing Dependencies..."
php composer.phar install --ignore-platform-reqs || composer install --ignore-platform-reqs

echo ""
echo "Step 2: Setting up Environment..."
php artisan key:generate

echo ""
echo "Step 3: Creating Database..."
mkdir -p database
touch database/database.sqlite

echo ""
echo "Step 4: Running Migrations..."
php artisan migrate --force

echo ""
echo "Step 5: Seeding Database (Optional - Creates demo user)..."
php artisan db:seed

echo ""
echo "✅ Setup Selesai!"
echo ""
echo "⚠️  PENTING: Edit file .env dan isi TMDB API Key:"
echo "   TMDB_API_KEY=your_api_key_here"
echo ""
echo "📝 Untuk mendapatkan TMDB API Key:"
echo "   1. Buka https://www.themoviedb.org/settings/api"
echo "   2. Daftar/Login ke TMDB"
echo "   3. Request API Key"
echo "   4. Copy API Key ke .env"
echo ""
echo "🚀 Untuk menjalankan aplikasi:"
echo "   php artisan serve"
echo ""
echo "💻 Akses aplikasi di:"
echo "   http://localhost:8000"
echo ""
echo "🔓 Login dengan akun demo:"
echo "   Email: demo@movielist.test"
echo "   Password: password123"
echo ""
echo "📚 Dokumentasi:"
echo "   - README.md untuk overview"
echo "   - SETUP_INSTRUCTIONS.md untuk troubleshooting"
echo "   - COMPLETION_SUMMARY.md untuk fitur lengkap"
