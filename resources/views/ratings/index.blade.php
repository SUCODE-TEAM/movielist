<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Saya - MovieList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .ratings-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .ratings-header h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 30px;
        }
        .rating-item {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
        }
        .rating-poster {
            width: 100px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .rating-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .rating-content {
            flex: 1;
        }
        .rating-title {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .rating-value {
            color: #ffb700;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .rating-review {
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .rating-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #ff6b6b;
            color: white;
        }
        .btn-primary:hover {
            background: #ee5a6f;
        }
        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.3);
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

    <div class="ratings-container">
        <div class="ratings-header">
            <h1>⭐ Rating Saya</h1>
        </div>

        @if($ratings->count() > 0)
            <div>
                @foreach($ratings as $rating)
                    <div class="rating-item">
                        <div class="rating-poster">
                            <img src="https://image.tmdb.org/t/p/w500{{ $rating->poster_path ?? '/placeholder.jpg' }}" alt="">
                        </div>
                        <div class="rating-content">
                            <div class="rating-title">{{ $rating->title ?? 'Film' }}</div>
                            <div class="rating-value">⭐ Rating: {{ $rating->rating }}/10</div>
                            @if($rating->review)
                                <div class="rating-review">{{ $rating->review }}</div>
                            @endif
                            <div class="rating-actions">
                                <a href="{{ route('movies.show', $rating->movie_id) }}" class="btn btn-primary">Lihat Film</a>
                                <button class="btn btn-secondary" onclick="deleteRating({{ $rating->movie_id }})">Hapus Rating</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($ratings->hasPages())
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 40px;">
                    {{ $ratings->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">⭐</div>
                <h3>Belum ada rating</h3>
                <p>Mulai beri rating untuk film yang Anda tonton!</p>
                <a href="{{ route('movies.index') }}" style="color: #ff6b6b; text-decoration: none; font-weight: 600; margin-top: 20px; display: inline-block;">← Kembali ke Beranda</a>
            </div>
        @endif
    </div>

    <script>
        function deleteRating(movieId) {
            if (confirm('Hapus rating ini?')) {
                fetch(`/ratings/${movieId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then(() => {
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>
