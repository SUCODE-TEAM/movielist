<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }} - MovieList</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .movie-backdrop {
            position: relative;
            height: 600px;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $movie["backdrop_path"] }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            color: white;
        }
        .movie-header {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 40px;
        }
        .movie-poster {
            width: 200px;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .movie-info h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .movie-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rating-stars {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 8px;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
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
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }
        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.3);
        }
        .content-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }
        .section-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
        }
        .overview {
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .genres {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        .genre-tag {
            background: rgba(255,107,107,0.2);
            color: #ff6b6b;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid rgba(255,107,107,0.3);
        }
        .cast-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .cast-card {
            text-align: center;
        }
        .cast-image {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
            background: rgba(255,255,255,0.1);
        }
        .cast-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .cast-name {
            color: #fff;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .cast-role {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
        }
        .recommendations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }
        .movie-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .movie-card:hover {
            transform: scale(1.05);
        }
        .movie-card img {
            width: 100%;
            height: 225px;
            object-fit: cover;
        }
        .movie-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            padding: 12px;
            color: #fff;
        }
        .movie-card-title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .movie-card-rating {
            font-size: 12px;
            color: #ffb700;
        }
        .reviews-section {
            margin-top: 40px;
        }
        .rating-form {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .star-rating {
            display: flex;
            gap: 8px;
            margin: 15px 0;
        }
        .star {
            font-size: 32px;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        .star:hover,
        .star.active {
            opacity: 1;
        }
        .review-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-user {
            font-weight: 600;
            color: #fff;
        }
        .review-rating {
            color: #ffb700;
        }
        .review-text {
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
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
                @auth
                    <a href="{{ route('watchlist.index') }}" class="nav-btn">📌 Watchlist</a>
                    <a href="{{ route('profile.show') }}" class="nav-btn">👤 {{ auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Login</a>
                    <a href="{{ route('register') }}" class="nav-btn">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Movie Backdrop Header -->
    <div class="movie-backdrop" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $movie["backdrop_path"] }}');">
        <div class="movie-header">
            <div class="movie-poster">
                <img src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
            </div>
            <div class="movie-info">
                <h1>{{ $movie['title'] }}</h1>
                <div class="movie-meta">
                    <div class="meta-item">
                        📅 {{ date('Y', strtotime($movie['release_date'])) }}
                    </div>
                    <div class="meta-item">
                        ⏱️ {{ $movie['runtime'] }} menit
                    </div>
                    <div class="rating-stars">
                        ⭐ {{ number_format($movie['vote_average'], 1) }}/10
                    </div>
                </div>
                <div class="genres">
                    @foreach($movie['genres'] as $genre)
                        <span class="genre-tag">{{ $genre['name'] }}</span>
                    @endforeach
                </div>
                @auth
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="toggleWatchlist()">
                            <span id="watchlistText">{{ $inWatchlist ? '✓ Dalam Watchlist' : '+ Tambah ke Watchlist' }}</span>
                        </button>
                        <button class="btn btn-secondary" onclick="document.getElementById('ratingForm').scrollIntoView({behavior: 'smooth'})">Beri Rating</button>
                    </div>
                @else
                    <div class="action-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login untuk Watchlist</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="content-section">
        <!-- Overview -->
        <div style="margin-bottom: 40px;">
            <h2 class="section-title">Ringkasan</h2>
            <p class="overview">{{ $movie['overview'] }}</p>
        </div>

        <!-- Cast -->
        @if($credits['cast'])
            <div style="margin-bottom: 40px;">
                <h2 class="section-title">Pemeran Utama</h2>
                <div class="cast-grid">
                    @foreach($credits['cast'] as $actor)
                        <div class="cast-card">
                            @if($actor['profile_path'])
                                <div class="cast-image">
                                    <img src="{{ $actor['profile_path'] }}" alt="{{ $actor['name'] }}">
                                </div>
                            @else
                                <div class="cast-image" style="background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;">
                                    <span style="color: rgba(255,255,255,0.5);">No Image</span>
                                </div>
                            @endif
                            <div class="cast-name">{{ $actor['name'] }}</div>
                            <div class="cast-role">{{ $actor['character'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Crew -->
        @if($credits['crew'])
            <div style="margin-bottom: 40px;">
                <h2 class="section-title">Kru</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                    @foreach($credits['crew'] as $person)
                        <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px;">
                            <div style="color: #fff; font-weight: 600;">{{ $person['name'] }}</div>
                            <div style="color: rgba(255,255,255,0.6); font-size: 12px;">{{ $person['job'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recommendations -->
        @if($recommendations)
            <div style="margin-bottom: 40px;">
                <h2 class="section-title">Rekomendasi Film Serupa</h2>
                <div class="recommendations-grid">
                    @foreach($recommendations as $movie)
                        <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card">
                            <img src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="movie-card-overlay">
                                <div class="movie-card-title">{{ $movie['title'] }}</div>
                                <div class="movie-card-rating">⭐ {{ number_format($movie['vote_average'], 1) }}/10</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ratings & Reviews -->
        @auth
            <div class="reviews-section" id="ratingForm">
                <h2 class="section-title">Beri Rating & Review</h2>
                <div class="rating-form">
                    <form id="ratingFormElement" onsubmit="submitRating(event)">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                        
                        <div>
                            <label style="color: rgba(255,255,255,0.9); font-weight: 600;">Rating</label>
                            <div class="star-rating" id="starRating">
                                @for($i = 1; $i <= 10; $i++)
                                    <span class="star" onclick="setRating({{ $i }})" data-rating="{{ $i }}">⭐</span>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="{{ $userRating?->rating ?? 0 }}">
                        </div>

                        <div style="margin-top: 15px;">
                            <label style="color: rgba(255,255,255,0.9); font-weight: 600; display: block; margin-bottom: 10px;">Review (Opsional)</label>
                            <textarea name="review" style="width: 100%; height: 100px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: #fff; padding: 12px; font-family: inherit;" placeholder="Tulis review Anda...">{{ $userRating?->review }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 15px; width: 100%;">Simpan Rating</button>
                    </form>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 40px; background: rgba(255,255,255,0.05); border-radius: 12px;">
                <p style="color: rgba(255,255,255,0.8); margin-bottom: 20px;">Login untuk memberikan rating dan review</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login Sekarang</a>
            </div>
        @endauth

        <!-- Reviews List -->
        @if($allRatings->count())
            <div style="margin-top: 40px;">
                <h2 class="section-title">Review dari User Lain</h2>
                <div>
                    @foreach($allRatings as $rating)
                        <div class="review-item">
                            <div class="review-header">
                                <div>
                                    <div class="review-user">{{ $rating->user->name }}</div>
                                    <div style="color: rgba(255,255,255,0.6); font-size: 12px;">{{ $rating->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="review-rating">⭐ {{ $rating->rating }}/10</div>
                            </div>
                            @if($rating->review)
                                <div class="review-text">{{ $rating->review }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleWatchlist() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            const movieId = {{ $movie['id'] }};
            const inWatchlist = {{ $inWatchlist ? 'true' : 'false' }};

            if (inWatchlist) {
                fetch(`/watchlist/${movieId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then(() => {
                    document.getElementById('watchlistText').textContent = '+ Tambah ke Watchlist';
                    location.reload();
                });
            } else {
                fetch('/watchlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        movie_id: movieId,
                        title: '{{ $movie["title"] }}',
                        overview: '{{ $movie["overview"] }}',
                        poster_path: '{{ $movie["poster_path"] }}',
                        vote_average: {{ $movie['vote_average'] }},
                        status: 'planning'
                    })
                }).then(() => {
                    document.getElementById('watchlistText').textContent = '✓ Dalam Watchlist';
                    location.reload();
                });
            }
        }

        function setRating(rating) {
            document.getElementById('ratingInput').value = rating;
            document.querySelectorAll('.star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function submitRating(event) {
            event.preventDefault();
            const rating = document.getElementById('ratingInput').value;
            
            if (!rating) {
                alert('Silakan pilih rating terlebih dahulu');
                return;
            }

            const formData = new FormData(document.getElementById('ratingFormElement'));
            
            fetch('/ratings', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    movie_id: {{ $movie['id'] }},
                    rating: rating,
                    review: formData.get('review')
                })
            }).then(response => response.json())
             .then(data => {
                alert('Rating berhasil disimpan!');
                location.reload();
             })
             .catch(error => console.error('Error:', error));
        }

        // Load existing rating
        window.addEventListener('load', () => {
            const currentRating = {{ $userRating?->rating ?? 0 }};
            if (currentRating > 0) {
                setRating(currentRating);
            }
        });
    </script>
</body>
</html>
