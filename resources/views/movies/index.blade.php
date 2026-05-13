<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MovieList - Platform terbaik untuk menemukan dan menonton film favorit Anda.">
    <title>MovieList - Nonton Film Gratis | Katalog Film Terlengkap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Menggunakan asset() untuk memanggil CSS di Laravel -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="bg-gradient-1"></div>
        <div class="bg-gradient-2"></div>
        <div class="bg-gradient-3"></div>
    </div>

    <!-- Navigation -->
    <nav id="navbar" class="navbar scrolled">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <span class="logo-icon">🎬</span>
                <span class="logo-text">Movie<span class="logo-accent">List</span></span>
            </a>
            <div class="nav-search">
                <div class="search-wrapper">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari film favorit kamu..." autocomplete="off">
                    <div id="searchResults" style="position: absolute; top: 100%; left: 0; right: 0; background: rgba(29, 53, 87, 0.95); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; margin-top: 5px; max-height: 300px; overflow-y: auto; display: none; z-index: 1000;">
                    </div>
                </div>
            </div>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('watchlist.index') }}" class="nav-btn">📌 Watchlist</a>
                    <a href="{{ route('ratings.index') }}" class="nav-btn">⭐ Ratings</a>
                    <a href="{{ route('profile.show') }}" class="nav-btn">👤 {{ auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">🔓 Login</a>
                    <a href="{{ route('register') }}" class="nav-btn" style="background: linear-gradient(135deg, #ff6b6b, #ee5a6f); color: white; padding: 8px 16px; border-radius: 8px;">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" style="padding-top: 80px;">
        
        @if(isset($error))
            <div style="background: rgba(229, 9, 20, 0.2); padding: 1rem; border-radius: 8px; margin: 2rem; text-align: center; border: 1px solid var(--primary);">
                <p>{{ $error }}</p>
                <small>Silakan isi <code>TMDB_API_KEY</code> di file <code>.env</code> Anda.</small>
            </div>
        @endif

        <!-- Trending Section -->
        <section class="movie-section">
            <div class="section-header">
                <h2 class="section-title"><span class="title-icon">🔥</span> Trending Minggu Ini</h2>
            </div>
            <div class="movie-carousel">
                <div class="movie-row">
                    @forelse($trending as $movie)
                        <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card" style="text-decoration: none;">
                            <img class="card-poster" src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="card-overlay">
                                <div class="card-rating">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span>{{ number_format($movie['vote_average'], 1) }}</span>
                                </div>
                                <div class="card-play-btn">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </div>
                                <h3 class="card-title">{{ $movie['title'] }}</h3>
                                <div class="card-meta">
                                    <span>{{ substr($movie['release_date'] ?? $movie['first_air_date'] ?? '', 0, 4) }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p style="color: var(--text-muted);">Tidak ada film ditemukan.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Popular Section -->
        <section class="movie-section">
            <div class="section-header">
                <h2 class="section-title"><span class="title-icon">💎</span> Film Populer</h2>
            </div>
            <div class="movie-carousel">
                <div class="movie-row">
                    @foreach($popular as $movie)
                        <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card" style="text-decoration: none;">
                            <img class="card-poster" src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="card-overlay">
                                <div class="card-rating">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span>{{ number_format($movie['vote_average'], 1) }}</span>
                                </div>
                                <div class="card-play-btn">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </div>
                                <h3 class="card-title">{{ $movie['title'] }}</h3>
                                <div class="card-meta">
                                    <span>{{ substr($movie['release_date'] ?? '', 0, 4) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Top Rated Section -->
        <section class="movie-section">
            <div class="section-header">
                <h2 class="section-title"><span class="title-icon">⭐</span> Rating Tertinggi</h2>
            </div>
            <div class="movie-carousel">
                <div class="movie-row">
                    @foreach($topRated as $movie)
                        <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card" style="text-decoration: none;">
                            <img class="card-poster" src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                            <div class="card-overlay">
                                <div class="card-rating">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span>{{ number_format($movie['vote_average'], 1) }}</span>
                                </div>
                                <div class="card-play-btn">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </div>
                                <h3 class="card-title">{{ $movie['title'] }}</h3>
                                <div class="card-meta">
                                    <span>{{ substr($movie['release_date'] ?? '', 0, 4) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <span class="logo-icon">🎬</span>
                <span class="logo-text">Movie<span class="logo-accent">List</span></span>
                <p class="footer-tagline">Platform terbaik untuk menemukan film favorit kamu</p>
            </div>
            <div class="footer-bottom">
                <p>© 2026 MovieList. Data disediakan oleh TMDB API. Aplikasi ini berbasis Laravel 13.</p>
            </div>
        </div>
    </footer>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        
                        if (data.movies.length === 0) {
                            searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: rgba(255,255,255,0.7);">Tidak ada hasil ditemukan</div>';
                        } else {
                            data.movies.forEach(movie => {
                                const div = document.createElement('div');
                                div.style.cssText = 'padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); cursor: pointer; display: flex; gap: 10px; align-items: center;';
                                div.innerHTML = `
                                    <img src="${movie.poster_path}" style="width: 40px; height: 60px; border-radius: 4px; object-fit: cover;">
                                    <div style="flex: 1;">
                                        <div style="color: #fff; font-weight: 600;">${movie.title}</div>
                                        <div style="color: rgba(255,255,255,0.6); font-size: 12px;">⭐ ${parseFloat(movie.vote_average).toFixed(1)}/10</div>
                                    </div>
                                `;
                                div.onclick = () => {
                                    window.location.href = `/movies/${movie.id}`;
                                };
                                searchResults.appendChild(div);
                            });
                        }
                        
                        searchResults.style.display = 'block';
                    })
                    .catch(error => console.error('Search error:', error));
            }, 300);
        });

        document.addEventListener('click', function(event) {
            if (event.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });

        // Make movie cards clickable
        document.querySelectorAll('.movie-card').forEach(card => {
            card.addEventListener('click', function() {
                const movieLink = this.querySelector('a');
                if (!movieLink) {
                    // If no link, we'll make it dynamic based on data attribute
                    const movieId = this.dataset.movieId;
                    if (movieId) {
                        window.location.href = `/movies/${movieId}`;
                    }
                }
            });
        });
    </script>
</body>
</html>
