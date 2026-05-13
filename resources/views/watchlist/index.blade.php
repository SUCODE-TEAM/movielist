<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - MovieList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .watchlist-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }
        .watchlist-header {
            margin-bottom: 40px;
        }
        .watchlist-header h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .watchlist-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #ff6b6b;
            margin-bottom: 8px;
        }
        .stat-label {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }
        .watchlist-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 10px 20px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.8);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .filter-btn:hover,
        .filter-btn.active {
            background: #ff6b6b;
            color: white;
            border-color: #ff6b6b;
        }
        .watchlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .watchlist-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .watchlist-item:hover {
            transform: translateY(-5px);
        }
        .watchlist-poster {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
        }
        .watchlist-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .watchlist-poster-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .watchlist-item:hover .watchlist-poster-overlay {
            opacity: 1;
        }
        .overlay-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .overlay-btn-primary {
            background: #ff6b6b;
            color: white;
        }
        .overlay-btn-primary:hover {
            background: #ee5a6f;
        }
        .overlay-btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .overlay-btn-secondary:hover {
            background: rgba(255,255,255,0.3);
        }
        .watchlist-info {
            padding: 15px;
        }
        .watchlist-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .watchlist-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .watchlist-rating {
            color: #ffb700;
            font-size: 12px;
        }
        .watchlist-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-planning {
            background: rgba(150, 150, 255, 0.2);
            color: #9696ff;
        }
        .status-watching {
            background: rgba(100, 200, 255, 0.2);
            color: #64c8ff;
        }
        .status-watched {
            background: rgba(100, 255, 150, 0.2);
            color: #64ff96;
        }
        .status-select {
            width: 100%;
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            padding: 6px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.7);
        }
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            color: rgba(255,255,255,0.9);
            margin-bottom: 10px;
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
                <a href="{{ route('profile.show') }}" class="nav-btn">👤 {{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="watchlist-container">
        <div class="watchlist-header">
            <h1>📌 Watchlist Saya</h1>
            
            <div class="watchlist-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Film</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['planning'] }}</div>
                    <div class="stat-label">Ingin Ditonton</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['watching'] }}</div>
                    <div class="stat-label">Sedang Ditonton</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['watched'] }}</div>
                    <div class="stat-label">Sudah Ditonton</div>
                </div>
            </div>

            <div class="watchlist-filters">
                <a href="{{ route('watchlist.index') }}" class="filter-btn {{ !isset($activeStatus) ? 'active' : '' }}">Semua</a>
                <a href="{{ route('watchlist.status', 'planning') }}" class="filter-btn {{ $activeStatus ?? '' === 'planning' ? 'active' : '' }}">Ingin Ditonton</a>
                <a href="{{ route('watchlist.status', 'watching') }}" class="filter-btn {{ $activeStatus ?? '' === 'watching' ? 'active' : '' }}">Sedang Ditonton</a>
                <a href="{{ route('watchlist.status', 'watched') }}" class="filter-btn {{ $activeStatus ?? '' === 'watched' ? 'active' : '' }}">Sudah Ditonton</a>
            </div>
        </div>

        @if($watchlist->count() > 0)
            <div class="watchlist-grid">
                @foreach($watchlist as $item)
                    <div class="watchlist-item">
                        <div class="watchlist-poster">
                            <img src="{{ $item->poster_path }}" alt="{{ $item->title }}">
                            <div class="watchlist-poster-overlay">
                                <a href="{{ route('movies.show', $item->movie_id) }}" class="overlay-btn overlay-btn-primary">Lihat Detail</a>
                                <button class="overlay-btn overlay-btn-secondary" onclick="removeFromWatchlist({{ $item->movie_id }})">Hapus</button>
                            </div>
                        </div>
                        <div class="watchlist-info">
                            <div class="watchlist-title">{{ $item->title }}</div>
                            <div class="watchlist-meta">
                                <span class="watchlist-rating">⭐ {{ number_format($item->vote_average, 1) }}/10</span>
                            </div>
                            <select class="status-select" onchange="updateWatchlistStatus({{ $item->movie_id }}, this.value)">
                                <option value="planning" {{ $item->status === 'planning' ? 'selected' : '' }}>Ingin Ditonton</option>
                                <option value="watching" {{ $item->status === 'watching' ? 'selected' : '' }}>Sedang Ditonton</option>
                                <option value="watched" {{ $item->status === 'watched' ? 'selected' : '' }}>Sudah Ditonton</option>
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($watchlist->hasPages())
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 40px;">
                    {{ $watchlist->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📽️</div>
                <h3>Watchlist masih kosong</h3>
                <p>Mulai tambahkan film ke watchlist Anda!</p>
                <a href="{{ route('movies.index') }}" style="color: #ff6b6b; text-decoration: none; font-weight: 600; margin-top: 20px; display: inline-block;">← Kembali ke Beranda</a>
            </div>
        @endif
    </div>

    <script>
        function removeFromWatchlist(movieId) {
            if (confirm('Hapus film ini dari watchlist?')) {
                fetch(`/watchlist/${movieId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then(() => {
                    location.reload();
                });
            }
        }

        function updateWatchlistStatus(movieId, status) {
            fetch(`/watchlist/${movieId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ status: status })
            }).then(() => {
                location.reload();
            });
        }
    </script>
</body>
</html>
