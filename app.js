const API_KEY = 'c88c94f12fcd99e29851e05850e1950f';
const BASE_URL = 'https://api.themoviedb.org/3';
const IMG_URL = 'https://media.themoviedb.org/t/p/w500';
const DONUT_IMG = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1d24'/%3E%3Ctext y='50%25' x='50%25' font-size='50' dominant-baseline='middle' text-anchor='middle'%3E🍩%3C/text%3E%3C/svg%3E";
// DOM Elements
const trendingRow = document.getElementById('trendingRow');
const nowPlayingRow = document.getElementById('nowPlayingRow');
const topRatedRow = document.getElementById('topRatedRow');
const upcomingRow = document.getElementById('upcomingRow');
const popularRow = document.getElementById('popularRow');
const loadingScreen = document.getElementById('loadingScreen');

// Fetch Movies on Load
document.addEventListener('DOMContentLoaded', () => {
    fetchAllCategories();
    
    // Fetch Genres
    fetchGenres();
    
    // Hide loading screen after 1.5 seconds
    setTimeout(() => {
        if (loadingScreen) loadingScreen.style.opacity = '0';
        setTimeout(() => {
            if (loadingScreen) loadingScreen.classList.add('hidden');
        }, 500);
    }, 1500);
});

function fetchAllCategories() {
    fetchMovies('/trending/movie/week', trendingRow);
    fetchMovies('/movie/now_playing', nowPlayingRow);
    fetchMovies('/movie/top_rated', topRatedRow);
    fetchMovies('/movie/upcoming', upcomingRow);
    fetchMovies('/movie/popular', popularRow);
    setupHero();
}

function fetchMovies(path, container) {
    if (!container) return;
    
    fetch(`${BASE_URL}${path}?api_key=${API_KEY}`)
        .then(res => res.json())
        .then(data => {
            if (data.results) {
                renderMovies(data.results, container);
            }
        })
        .catch(err => console.error('Error fetching movies:', err));
}

function renderMovies(movies, container) {
    container.innerHTML = '';
    
    movies.forEach(movie => {
        const movieCard = document.createElement('div');
        movieCard.classList.add('movie-card');
        
        const posterPath = movie.poster_path ? `${IMG_URL}${movie.poster_path}` : DONUT_IMG;
        const releaseYear = movie.release_date ? movie.release_date.substring(0, 4) : 'N/A';
        
        movieCard.innerHTML = `
            <img class="card-poster" src="${posterPath}" alt="${movie.title}">
            <div class="card-overlay">
                <div class="card-rating">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>${movie.vote_average.toFixed(1)}</span>
                </div>
                <div class="card-play-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </div>
                <h3 class="card-title">${movie.title}</h3>
                <div class="card-meta">
                    <span>${releaseYear}</span>
                </div>
            </div>
        `;
        
        movieCard.addEventListener('click', () => {
            openMovieDetail(movie.id, 'movie');
        });
        
        container.appendChild(movieCard);
    });
}

function setupHero() {
    fetch(`${BASE_URL}/movie/popular?api_key=${API_KEY}`)
        .then(res => res.json())
        .then(data => {
            if (data.results && data.results.length > 0) {
                const hero = data.results[0]; // Ambil film pertama yang paling populer
                
                document.getElementById('heroTitle').innerText = hero.title;
                document.getElementById('heroOverview').innerText = hero.overview;
                document.getElementById('heroRating').innerText = hero.vote_average.toFixed(1);
                document.getElementById('heroYear').innerText = hero.release_date.substring(0, 4);
                
                const backdropPath = hero.backdrop_path ? `https://media.themoviedb.org/t/p/original${hero.backdrop_path}` : '';
                document.getElementById('heroBackdrop').style.backgroundImage = backdropPath ? `url('${backdropPath}')` : '';
                
                window.heroMovieId = hero.id;
            }
        })
        .catch(err => console.error('Error setting up hero:', err));
}

// Carousel Scroll
function scrollCarousel(id, direction) {
    const container = document.getElementById(id).querySelector('.movie-row');
    const scrollAmount = 300;
    container.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}

function goHome() {
    window.location.reload();
}

let currentTrailerKey = null;
let currentMovieId = null;
let currentMovieTitle = null;
let currentPlayingId = null;
let currentMediaType = 'movie';

function openMovieDetail(id, type = 'movie') {
    if (!id) return;
    currentMovieId = id;
    currentMediaType = type;
    
    // Fetch detail, videos, credits, and similar
    fetch(`${BASE_URL}/${type}/${id}?api_key=${API_KEY}&append_to_response=videos,credits,similar`)
        .then(res => res.json())
        .then(movie => {
            const title = movie.title || movie.name;
            const releaseDate = movie.release_date || movie.first_air_date;
            const runtime = movie.runtime ? `${movie.runtime} mnt` : (movie.episode_run_time && movie.episode_run_time.length > 0 ? `${movie.episode_run_time[0]} mnt` : '');
            
            currentMovieTitle = title;
            // Update Modal UI
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalOverview').innerText = movie.overview || 'Sinopsis tidak tersedia.';
            document.getElementById('modalRating').innerText = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';
            document.getElementById('modalYear').innerText = releaseDate ? releaseDate.substring(0, 4) : 'N/A';
            document.getElementById('modalRuntime').innerText = runtime;
            
            // Poster and Backdrop
            const posterPath = movie.poster_path ? `${IMG_URL}${movie.poster_path}` : DONUT_IMG;
            const backdropPath = movie.backdrop_path ? `https://media.themoviedb.org/t/p/original${movie.backdrop_path}` : '';
            
            document.getElementById('modalPoster').src = posterPath;
            document.getElementById('modalBackdrop').style.backgroundImage = backdropPath ? `url('${backdropPath}')` : '';
            
            // Genres
            const genresContainer = document.getElementById('modalGenres');
            if (genresContainer) {
                genresContainer.innerHTML = movie.genres.map(g => `<span class="modal-genre-tag">${g.name}</span>`).join('');
            }
            
            // TV Controls
            const tvControls = document.getElementById('tvControls');
            const seasonSelect = document.getElementById('seasonSelect');
            if (type === 'tv' && movie.number_of_seasons && tvControls) {
                tvControls.style.display = 'flex';
                tvControls.classList.remove('hidden');
                
                seasonSelect.innerHTML = '';
                for (let i = 1; i <= movie.number_of_seasons; i++) {
                    // Cek jika API memberikan special season (0), kita mulai dari 1 saja agar rapi
                    seasonSelect.innerHTML += `<option value="${i}">Season ${i}</option>`;
                }
                loadEpisodes();
            } else if (tvControls) {
                tvControls.style.display = 'none';
                tvControls.classList.add('hidden');
            }
            
            // Cast
            const castContainer = document.getElementById('modalCast');
            if (castContainer && movie.credits && movie.credits.cast) {
                const topCast = movie.credits.cast.slice(0, 10);
                castContainer.innerHTML = topCast.map(actor => {
                    const profileImg = actor.profile_path ? `${IMG_URL}${actor.profile_path}` : DONUT_IMG;
                    return `
                    <div class="cast-item">
                        <img src="${profileImg}" alt="${actor.name}" class="cast-img">
                        <div class="cast-name">${actor.name}</div>
                        <div class="cast-char">${actor.character}</div>
                    </div>`;
                }).join('');
            }
            
            // Trailer
            const playBtn = document.getElementById('modalPlayBtn');
            currentTrailerKey = null;
            if (movie.videos && movie.videos.results) {
                const trailer = movie.videos.results.find(vid => vid.type === 'Trailer' && vid.site === 'YouTube');
                if (trailer) {
                    currentTrailerKey = trailer.key;
                }
            }
            
            if (playBtn) {
                playBtn.style.display = currentTrailerKey ? 'flex' : 'none';
            }
            
            // Details
            const detailsContainer = document.getElementById('modalDetails');
            if (detailsContainer) {
                const status = movie.status || '-';
                const releaseDateObj = movie.release_date || movie.first_air_date || '';
                const lang = movie.original_language ? movie.original_language.toUpperCase() : '-';
                const companies = movie.production_companies && movie.production_companies.length > 0 
                    ? movie.production_companies.map(c => c.name).join(', ') 
                    : '-';
                    
                detailsContainer.innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">${status}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Rilis</span>
                        <span class="detail-value">${releaseDateObj || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bahasa Asli</span>
                        <span class="detail-value">${lang}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Rumah Produksi</span>
                        <span class="detail-value">${companies}</span>
                    </div>
                `;
            }

            // Similar Movies
            const similarContainer = document.getElementById('modalSimilar');
            if (similarContainer && movie.similar && movie.similar.results) {
                const similarMovies = movie.similar.results.slice(0, 10);
                if (similarMovies.length > 0) {
                    similarContainer.innerHTML = similarMovies.map(sim => {
                        const simPoster = sim.poster_path ? `${IMG_URL}${sim.poster_path}` : DONUT_IMG;
                        const simTitle = sim.title || sim.name;
                        const simYear = (sim.release_date || sim.first_air_date || '').substring(0, 4);
                        return `
                            <div class="movie-card" onclick="openMovieDetail(${sim.id}, '${type}')" style="flex: 0 0 160px; height: 280px; position: relative;">
                                <img class="card-poster" src="${simPoster}" alt="${simTitle}" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">
                                <div class="card-overlay" style="position: absolute; bottom: 0; width: 100%; padding: 1rem; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                                    <h3 class="card-title" style="font-size: 0.9rem; margin: 0; text-align: left;">${simTitle}</h3>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">${simYear}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    similarContainer.innerHTML = '<p style="color: var(--text-muted);">Tidak ada konten serupa yang ditemukan.</p>';
                }
            }

            // Show modal
            const modal = document.getElementById('movieModal');
            if (modal) modal.classList.remove('hidden');
        })
        .catch(err => console.error('Error fetching details:', err));
}

window.loadEpisodes = function() {
    if (currentMediaType !== 'tv' || !currentMovieId) return;
    const season = document.getElementById('seasonSelect').value;
    const episodeSelect = document.getElementById('episodeSelect');
    
    fetch(`${BASE_URL}/tv/${currentMovieId}/season/${season}?api_key=${API_KEY}`)
        .then(res => res.json())
        .then(data => {
            if (episodeSelect) {
                episodeSelect.innerHTML = '';
                if (data.episodes) {
                    data.episodes.forEach(ep => {
                        episodeSelect.innerHTML += `<option value="${ep.episode_number}">Episode ${ep.episode_number}: ${ep.name}</option>`;
                    });
                }
            }
        });
}

function closeModal() {
    const modal = document.getElementById('movieModal');
    if (modal) modal.classList.add('hidden');
}

function playTrailer() {
    if (!currentTrailerKey) return;
    
    const trailerModal = document.getElementById('trailerModal');
    const trailerPlayer = document.getElementById('trailerPlayer');
    const serverSwitcher = document.getElementById('serverSwitcher');
    
    if (serverSwitcher) serverSwitcher.style.display = 'none';
    
    if (trailerModal && trailerPlayer) {
        trailerPlayer.innerHTML = '';
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube.com/embed/${currentTrailerKey}?autoplay=1`;
        iframe.frameBorder = '0';
        iframe.allowFullscreen = true;
        iframe.setAttribute('allow', 'autoplay; encrypted-media; fullscreen; picture-in-picture');
        iframe.setAttribute('allowfullscreen', 'true');
        iframe.setAttribute('webkitallowfullscreen', 'true');
        iframe.setAttribute('mozallowfullscreen', 'true');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        trailerPlayer.appendChild(iframe);
        trailerModal.classList.remove('hidden');
    }
}

function closeTrailer() {
    const trailerModal = document.getElementById('trailerModal');
    const trailerPlayer = document.getElementById('trailerPlayer');
    
    if (trailerModal && trailerPlayer) {
        trailerModal.classList.add('hidden');
        trailerPlayer.innerHTML = ''; // Stop video playing
        window.onbeforeunload = null; 
        window.open = window._originalOpen || window.open; // Kembalikan fungsi window.open
    }
}

function playFullMovie() {
    if (!currentMovieId) return;
    currentPlayingId = currentMovieId;
    openVideoModal();
}

function playFullMovieHero() {
    if (!window.heroMovieId) return;
    currentPlayingId = window.heroMovieId;
    currentMediaType = 'movie';
    openVideoModal();
}

// Fungsi untuk membuka modal video secara langsung
function openVideoModal() {
    if (!currentPlayingId) return;
    
    const trailerModal = document.getElementById('trailerModal');
    const trailerPlayer = document.getElementById('trailerPlayer');
    const serverSwitcher = document.getElementById('serverSwitcher');
    const adShield = document.getElementById('adShield');
    
    if (serverSwitcher) serverSwitcher.style.display = 'flex';
    if (trailerPlayer) trailerPlayer.innerHTML = '';
    
    // Blokir fungsi pembuka tab baru
    window.open = function() { return null; };
    window.onbeforeunload = function() { return "Tetap di sini?"; };
    
    // AKTIFKAN PERISAI RINGAN (2 Lapis)
    if (adShield) {
        adShield.style.display = 'block';
        let clickCount = 0;
        adShield.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            clickCount++;
            if (clickCount >= 2) {
                this.style.display = 'none';
            }
        };
    }
    
    let url = '';
    if (currentMediaType === 'tv') {
        const season = document.getElementById('seasonSelect') ? document.getElementById('seasonSelect').value : 1;
        const episode = document.getElementById('episodeSelect') ? document.getElementById('episodeSelect').value : 1;
        url = `https://vidsrc.cc/v2/embed/tv/${currentPlayingId}/${season}/${episode}`;
    } else {
        url = `https://vidsrc.cc/v2/embed/movie/${currentPlayingId}`;
    }
    
    if (trailerModal && trailerPlayer) {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.frameBorder = '0';
        iframe.allowFullscreen = true;
        iframe.setAttribute('allow', 'autoplay; encrypted-media; fullscreen; picture-in-picture');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        trailerPlayer.appendChild(iframe);
        trailerModal.classList.remove('hidden');
    }
}

// UI Interaction Functions
function toggleMobileMenu() {
    const navSearch = document.getElementById('navSearch');
    if (navSearch) {
        navSearch.classList.toggle('active');
    }
}

function toggleGenrePanel() {
    const genrePanel = document.getElementById('genrePanel');
    if (genrePanel) {
        if (genrePanel.classList.contains('hidden')) {
            genrePanel.classList.remove('hidden');
            setTimeout(() => genrePanel.classList.add('active'), 10);
        } else {
            genrePanel.classList.remove('active');
            setTimeout(() => genrePanel.classList.add('hidden'), 400);
        }
    }
}

function showWatchlist() {
    alert("Fitur Watchlist akan aktif setelah database backend dikonfigurasi.");
}

// Search Functionality
let searchTimeout = null;

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const searchSuggestions = document.getElementById('searchSuggestions');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
                
                // Debounce
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 500);
            } else {
                clearBtn.classList.add('hidden');
                searchSuggestions.classList.add('hidden');
                searchSuggestions.innerHTML = '';
            }
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.add('hidden');
            }
        });
        
        // Show suggestions again if focusing on input that has text
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim().length > 0 && searchSuggestions.innerHTML !== '') {
                searchSuggestions.classList.remove('hidden');
            }
        });
    }
});

function performSearch(query) {
    // Gunakan endpoint /search/multi agar bisa mencari FILM dan SERIAL TV sekaligus
    fetch(`${BASE_URL}/search/multi?api_key=${API_KEY}&query=${encodeURIComponent(query)}&language=en-US&page=1`)
        .then(res => res.json())
        .then(data => {
            const searchSuggestions = document.getElementById('searchSuggestions');
            if (data.results && data.results.length > 0) {
                // Filter hanya movie dan tv (hindari person)
                let items = data.results.filter(item => item.media_type === 'movie' || item.media_type === 'tv');
                items = items.slice(0, 6); // Limit to 6 suggestions
                
                if (items.length > 0) {
                    searchSuggestions.innerHTML = items.map(item => {
                        const posterPath = item.poster_path ? `${IMG_URL}${item.poster_path}` : DONUT_IMG;
                        const title = item.title || item.name;
                        const releaseDate = item.release_date || item.first_air_date;
                        const releaseYear = releaseDate ? releaseDate.substring(0, 4) : '';
                        const typeLabel = item.media_type === 'tv' ? '<span style="font-size: 0.7rem; background: var(--primary); padding: 0.1rem 0.3rem; border-radius: 4px; margin-left: 0.5rem;">TV</span>' : '';
                        
                        return `
                            <div class="suggestion-item" onclick="openSearchResult(${item.id}, '${item.media_type}')">
                                <img src="${posterPath}" alt="${title}" class="suggestion-poster">
                                <div class="suggestion-info">
                                    <span class="suggestion-title">${title} ${typeLabel}</span>
                                    <span class="suggestion-year">${releaseYear}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                    searchSuggestions.classList.remove('hidden');
                } else {
                    searchSuggestions.innerHTML = `<div class="suggestion-item"><div class="suggestion-info"><span class="suggestion-title">Pencarian tidak ditemukan</span></div></div>`;
                    searchSuggestions.classList.remove('hidden');
                }
            } else {
                searchSuggestions.innerHTML = `<div class="suggestion-item"><div class="suggestion-info"><span class="suggestion-title">Pencarian tidak ditemukan</span></div></div>`;
                searchSuggestions.classList.remove('hidden');
            }
        })
        .catch(err => console.error('Error searching:', err));
}

function openSearchResult(id, type) {
    const searchSuggestions = document.getElementById('searchSuggestions');
    searchSuggestions.classList.add('hidden');
    openMovieDetail(id, type);
}

// Harus tersedia secara global untuk onClick di HTML
window.clearSearch = function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const searchSuggestions = document.getElementById('searchSuggestions');
    
    if (searchInput) searchInput.value = '';
    if (clearBtn) clearBtn.classList.add('hidden');
    if (searchSuggestions) {
        searchSuggestions.classList.add('hidden');
        searchSuggestions.innerHTML = '';
    }
};

// Genre Functionality
function fetchGenres() {
    fetch(`${BASE_URL}/genre/movie/list?api_key=${API_KEY}&language=en-US`)
        .then(res => res.json())
        .then(data => {
            const genreList = document.getElementById('genreList');
            if (genreList && data.genres) {
                genreList.innerHTML = data.genres.map(genre => 
                    `<button class="genre-item" onclick="openGenre(${genre.id}, '${genre.name}')">${genre.name}</button>`
                ).join('');
            }
        })
        .catch(err => console.error('Error fetching genres:', err));
}

window.openGenre = function(genreId, genreName) {
    // Tutup panel
    const genrePanel = document.getElementById('genrePanel');
    if (genrePanel) {
        genrePanel.classList.remove('active');
        setTimeout(() => genrePanel.classList.add('hidden'), 400);
    }
    
    // Sembunyikan semua section utama
    document.getElementById('heroSection').style.display = 'none';
    const movieSections = document.querySelectorAll('.movie-section');
    movieSections.forEach(sec => sec.style.display = 'none');
    
    // Tampilkan hasil pencarian genre
    const resultsSection = document.getElementById('genreResultsSection');
    const resultsTitle = document.getElementById('genreResultsTitle');
    const resultsGrid = document.getElementById('genreResultsGrid');
    
    resultsSection.classList.remove('hidden');
    resultsTitle.innerHTML = `<span style="display:flex;align-items:center;gap:0.5rem;"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Genre: <span style="color: white; font-weight: bold;">${genreName}</span></span>`;
    resultsGrid.innerHTML = '<div style="text-align: center; width: 100%; grid-column: 1 / -1;">Memuat film...</div>';
    
    // Ambil data film berdasarkan genre (bisa dicampur TV dan Movie tapi kita pakai discover/movie dulu)
    fetch(`${BASE_URL}/discover/movie?api_key=${API_KEY}&with_genres=${genreId}&sort_by=popularity.desc&page=1`)
        .then(res => res.json())
        .then(data => {
            if (data.results && data.results.length > 0) {
                renderMovies(data.results, resultsGrid);
            } else {
                resultsGrid.innerHTML = '<div style="text-align: center; width: 100%; grid-column: 1 / -1;">Tidak ada film ditemukan.</div>';
            }
        })
        .catch(err => console.error('Error fetching genre movies:', err));
};

window.toggleCustomFullscreen = function() {
    const player = document.getElementById('trailerPlayer');
    if (!document.fullscreenElement && !document.webkitFullscreenElement) {
        if (player.requestFullscreen) {
            player.requestFullscreen();
        } else if (player.webkitRequestFullscreen) {
            player.webkitRequestFullscreen();
        } else if (player.msRequestFullscreen) {
            player.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
};

