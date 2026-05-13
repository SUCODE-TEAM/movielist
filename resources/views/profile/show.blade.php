<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - MovieList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .profile-header {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 40px;
            text-align: center;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto 20px;
        }
        .profile-name {
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .profile-email {
            color: rgba(255,255,255,0.7);
            margin-bottom: 20px;
        }
        .profile-bio {
            color: rgba(255,255,255,0.8);
            margin-bottom: 30px;
        }
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 8px;
        }
        .stat-box-number {
            font-size: 24px;
            font-weight: 700;
            color: #ff6b6b;
        }
        .stat-box-label {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }
        .profile-section {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .section-title {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            color: #fff;
            font-family: inherit;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255,255,255,0.5);
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff6b6b;
            background: rgba(255,255,255,0.15);
        }
        .button-group {
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
            flex: 1;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }
        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            flex: 1;
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.3);
        }
        .quick-links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .quick-link {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s;
        }
        .quick-link:hover {
            transform: translateY(-5px);
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, rgba(29, 53, 87, 0.95) 0%, rgba(40, 35, 76, 0.95) 100%); min-height: 100vh;">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <span class="logo-icon">🎬</span>
                <span class="logo-text">Movie<span class="logo-accent">List</span></span>
            </a>
            <div class="nav-actions">
                <a href="{{ route('watchlist.index') }}" class="nav-btn">📌 Watchlist</a>
                <a href="{{ route('profile.show') }}" class="nav-btn">👤 Profil</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            @if($user->bio)
                <div class="profile-bio">{{ $user->bio }}</div>
            @endif

            <div class="profile-stats">
                <div class="stat-box">
                    <div class="stat-box-number">{{ $stats['watchlist_count'] }}</div>
                    <div class="stat-box-label">Watchlist</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-number">{{ $stats['watched_count'] }}</div>
                    <div class="stat-box-label">Ditonton</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-number">{{ $stats['ratings_count'] }}</div>
                    <div class="stat-box-label">Rating</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(100, 255, 150, 0.2); border: 1px solid #64ff96; border-radius: 8px; padding: 15px; margin-bottom: 30px; color: #64ff96;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit Profile -->
        <div class="profile-section">
            <h2 class="section-title">⚙️ Edit Profil</h2>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                    @error('name')
                        <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio">Bio (Opsional)</label>
                    <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan tentang diri Anda...">{{ $user->bio }}</textarea>
                    @error('bio')
                        <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <a href="{{ route('watchlist.index') }}" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">📌 Lihat Watchlist</a>
                </div>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="profile-section">
            <h2 class="section-title">⚡ Akses Cepat</h2>
            <div class="quick-links">
                <a href="{{ route('watchlist.index') }}" class="quick-link">📌 Watchlist</a>
                <a href="{{ route('ratings.index') }}" class="quick-link">⭐ Rating Saya</a>
                <a href="{{ route('movies.index') }}" class="quick-link">🎬 Cari Film</a>
                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <button type="submit" class="quick-link" style="border: none; background: rgba(255, 107, 107, 0.2); cursor: pointer;">🚪 Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
