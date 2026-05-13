# Setup Instructions untuk MovieList Laravel 13

Sebelum menjalankan aplikasi, pastikan Anda telah menyelesaikan langkah-langkah berikut:

## 1. Install PHP & Composer

- **PHP 8.3+** - Download dari https://www.php.net/downloads
- **Composer** - Download dari https://getcomposer.org/download/

Pastikan keduanya sudah di PATH system Anda.

## 2. Install Dependencies

```bash
cd c:\Movielist
composer install
```

Jika ada error SSL/TLS:
```bash
composer install --ignore-platform-reqs
```

## 3. Setup Environment

Copy `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Generate app key:
```bash
php artisan key:generate
```

## 4. Setup TMDB API

1. Buka https://www.themoviedb.org/settings/api
2. Daftar akun TMDB jika belum punya
3. Request API key
4. Edit file `.env` dan isi:
```env
TMDB_API_KEY=your_api_key_here
TMDB_BASE_URL=https://api.themoviedb.org/3
```

## 5. Setup Database

Buat folder `database/` jika belum ada:
```bash
mkdir database
```

Buat file database SQLite:
```bash
touch database/database.sqlite
```

Edit `.env` jika perlu:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## 6. Run Migrations

```bash
php artisan migrate
```

Atau jika ingin dengan seeding:
```bash
php artisan migrate --seed
```

## 7. Run Development Server

```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

## 8. Login dengan Demo Account

Jika Anda menjalankan seeding:
- **Email**: demo@movielist.test
- **Password**: password123

## 📋 Checklist

- [ ] PHP 8.3+ terinstall
- [ ] Composer terinstall
- [ ] Dependencies sudah diinstall (`composer install`)
- [ ] `.env` file sudah dikonfigurasi
- [ ] App key sudah di-generate
- [ ] TMDB API key sudah diisi
- [ ] Database migrations sudah dijalankan
- [ ] Development server berjalan

## 🆘 Troubleshooting

### "Could not open input file: artisan"
- Pastikan Anda berada di direktori project: `cd c:\Movielist`
- Pastikan dependencies sudah diinstall: `composer install`

### "TMDB API Error"
- Verifikasi TMDB_API_KEY di `.env` sudah benar
- Restart development server
- Test API key di https://api.themoviedb.org/3/movie/550?api_key=YOUR_KEY

### "Database Error"
- Pastikan SQLite extension sudah enabled di PHP
- Check PHP version: `php -v` (harus 8.3+)
- Jalankan: `php artisan migrate:fresh --seed`

### "composer.phar OpenSSL Error"
Gunakan flag ini:
```bash
php composer.phar install --ignore-platform-reqs
```

Atau install ulang PHP dengan OpenSSL extension.

## 📚 Resources

- Laravel Documentation: https://laravel.com/docs/13
- TMDB API: https://developer.themoviedb.org/docs
- PHP Official: https://www.php.net/

---

Jika sudah selesai setup, aplikasi siap digunakan! Enjoy! 🎬
