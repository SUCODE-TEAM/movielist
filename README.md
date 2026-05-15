# 🎬 MovieList - Aplikasi Film Terlengkap

Selamat datang di MovieList! Aplikasi web modern untuk menemukan, menonton, dan mengelola film favorit Anda menggunakan **Laravel 13** dan **TMDB API**.

## 🚀 Fitur Utama

- ✅ **Browsing Film** - Lihat film trending, populer, top rated, dan yang akan datang
- ✅ **Search Real-time** - Cari film dengan search engine yang cepat
- ✅ **Detail Film Lengkap** - Informasi lengkap tentang film (sinopsis, cast, crew, budget, revenue, rating)
- ✅ **Rating & Review** - Beri rating dan tulis review untuk film yang Anda tonton
- ✅ **Watchlist** - Tambahkan film ke watchlist dengan status (ingin ditonton, sedang ditonton, sudah ditonton)
- ✅ **User Profiles** - Profil pengguna dengan statistik watchlist dan rating
- ✅ **Authentication** - Sistem login dan register yang aman
- ✅ **Responsive Design** - Desain yang indah dan responsif untuk semua device

## 💻 Tech Stack

- **Framework**: Laravel 13
- **Language**: PHP 8.3+
- **Database**: SQLite (atau bisa diganti dengan MySQL)
- **API**: TMDB (The Movie Database)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **CSS Styling**: Modern gradient dan glassmorphism design

## 🛠️ Instalasi & Setup

### 1. Clone Project
```bash
cd c:\Movielist
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Generate App Key
```bash
php artisan key:generate
```

### 4. Setup Database
Buat file `database/database.sqlite`:
```bash
touch database/database.sqlite
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Run Seeder (Optional)
```bash
php artisan db:seed
```

### 7. Get TMDB API Key
1. Buka https://www.themoviedb.org/settings/api
2. Daftar / Login ke akun TMDB Anda
3. Request API key
4. Copy API key Anda

### 8. Update .env File
Edit file `.env` dan isi konfigurasi TMDB:
```env
TMDB_API_KEY=your_api_key_here
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_READ_ACCESS_TOKEN=your_read_access_token
```

### 9. Jalankan Development Server
```bash
php artisan serve
```

Buka browser dan akses: http://localhost:8000

## 📁 Struktur Project

```
MovieList/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── MovieController.php          # Controller film
│   │       ├── WatchlistController.php      # Controller watchlist
│   │       ├── RatingController.php         # Controller rating
│   │       ├── ProfileController.php        # Controller profil
│   │       └── Auth/
│   │           ├── LoginController.php      # Controller login
│   │           └── RegisterController.php   # Controller register
│   └── Models/
│       ├── User.php                         # Model user
│       ├── Watchlist.php                    # Model watchlist
│       └── Rating.php                       # Model rating
├── database/
│   ├── migrations/                          # File migrasi database
│   ├── factories/                           # Factory untuk seeding
│   └── seeders/                             # Seeder data
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php              # Halaman login
│       │   └── register.blade.php           # Halaman register
│       ├── movies/
│       │   ├── index.blade.php              # Halaman utama
│       │   └── show.blade.php               # Halaman detail film
│       ├── watchlist/
│       │   └── index.blade.php              # Halaman watchlist
│       ├── ratings/
│       │   └── index.blade.php              # Halaman rating saya
│       └── profile/
│           └── show.blade.php               # Halaman profil
├── routes/
│   └── web.php                              # Routes
├── public/
│   └── css/
│       └── styles.css                       # Styling
├── config/
│   └── services.php                         # Konfigurasi layanan
├── .env                                     # Konfigurasi environment
└── composer.json                            # Dependencies

```

## 🗺️ Route Map

### Public Routes
- `GET /` - Halaman utama (browse film)
- `GET /movies/{id}` - Detail film
- `GET /api/search?q=...` - Search API

### Auth Routes (Unauthenticated)
- `GET /register` - Form register
- `POST /register` - Submit register
- `GET /login` - Form login
- `POST /login` - Submit login

### Protected Routes (Authenticated)
- `POST /logout` - Logout
- `GET /watchlist` - Lihat watchlist
- `POST /watchlist` - Tambah ke watchlist
- `PUT /watchlist/{id}` - Update status watchlist
- `DELETE /watchlist/{id}` - Hapus dari watchlist
- `GET /watchlist/status/{status}` - Filter by status
- `POST /ratings` - Tambah rating
- `DELETE /ratings/{id}` - Hapus rating
- `GET /my-ratings` - Lihat semua rating
- `GET /profile` - Lihat profil
- `PUT /profile` - Update profil

## 🔐 Database Schema

### Users Table
```sql
- id (Primary Key)
- name
- email (Unique)
- password
- avatar
- bio
- email_verified_at
- remember_token
- timestamps
```

### Watchlists Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- movie_id (TMDB Movie ID)
- title
- overview
- poster_path
- vote_average
- status (watching/watched/planning)
- timestamps
- Unique(user_id, movie_id)
```

### Ratings Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- movie_id (TMDB Movie ID)
- rating (1-10)
- review (Text)
- timestamps
- Unique(user_id, movie_id)
```

## 🧪 Test Data

Akun Demo:
- **Email**: demo@movielist.test
- **Password**: password123

## 📚 API Integration

### TMDB API Endpoints yang Digunakan
- `GET /trending/movie/week` - Film trending
- `GET /movie/popular` - Film populer
- `GET /movie/now_playing` - Film sedang tayang
- `GET /movie/top_rated` - Film rating tertinggi
- `GET /movie/upcoming` - Film yang akan datang
- `GET /movie/{id}` - Detail film
- `GET /movie/{id}/credits` - Cast dan crew
- `GET /movie/{id}/recommendations` - Rekomendasi
- `GET /search/movie` - Pencarian film

## 🎨 UI/UX Features

- 🌙 Dark theme dengan glassmorphism design
- 📱 Fully responsive untuk mobile, tablet, desktop
- ⚡ Real-time search dengan debounce
- 🎭 Smooth animations dan transitions
- 📊 Interactive star rating system
- 🎯 Clean navigation dan user-friendly interface

## 🔧 Troubleshooting

### API Key Error
- Pastikan TMDB_API_KEY sudah diisi di .env
- Restart server dengan `php artisan serve`

### Database Error
- Buat folder `database/` jika belum ada
- Jalankan `php artisan migrate:fresh --seed` untuk reset database

### 404 Not Found
- Pastikan route sudah didefinisikan di `routes/web.php`
- Clear cache dengan `php artisan config:clear`

## 📖 Dokumentasi Lebih Lanjut

- [Laravel Documentation](https://laravel.com/docs)
- [TMDB API Documentation](https://developer.themoviedb.org/docs)
- [PHP Documentation](https://www.php.net/docs.php)

## 📝 License

MIT License. Bebas untuk dikembangkan dan digunakan.

## 👨‍💻 Developed with Laravel 13

Aplikasi ini dibangun dengan **Laravel 13** dan mengikuti best practices Laravel modern.

---

**Happy Movie Watching! 🎬🍿**

---

## 🛡️ Fitur Anti-Iklan (AdBlocker)

Project ini memakai perlindungan bawaan aplikasi, jadi user tidak perlu memasang Tampermonkey, uBlock, atau adblocker dari device masing-masing:
1.  **Service Worker Filter**: Memblokir request iklan/tracker dari halaman MovieList.
2.  **Built-in Playback Guard**: Menutup popup dan request iklan saat player aktif.
3.  **Sandboxed Fallback Iframe**: Fallback `vidsrc` berjalan tanpa izin popup dan tanpa izin navigasi keluar tab.
4.  **Direct Stream First**: Resolver `/api/resolve` tetap diprioritaskan agar video diputar lewat player internal tanpa UI iklan embed.

---

