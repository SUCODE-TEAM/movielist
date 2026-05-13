const API_KEY = 'c88c94f12fcd99e29851e05850e1950f';
const BASE_URL = 'https://api.themoviedb.org/3';
const IMG_URL = 'https://image.themoviedb.org/t/p/w500';

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
        
        const posterPath = movie.poster_path ? `${IMG_URL}${movie.poster_path}` : 'https://via.placeholder.com/200x300?text=No+Poster';
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
            openMovieDetail(movie.id);
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
                
                const backdropPath = hero.backdrop_path ? `https://image.themoviedb.org/t/p/original${hero.backdrop_path}` : '';
                document.getElementById('heroBackdrop').style.backgroundImage = `url('${backdropPath}')`;
                
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

function openMovieDetail(id) {
    if (!id) return;
    currentMovieId = id;
    
    // Fetch detail, videos, and credits
    fetch(`${BASE_URL}/movie/${id}?api_key=${API_KEY}&append_to_response=videos,credits`)
        .then(res => res.json())
        .then(movie => {
            currentMovieTitle = movie.title;
            // Update Modal UI
            document.getElementById('modalTitle').innerText = movie.title;
            document.getElementById('modalOverview').innerText = movie.overview || 'Sinopsis tidak tersedia.';
            document.getElementById('modalRating').innerText = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';
            document.getElementById('modalYear').innerText = movie.release_date ? movie.release_date.substring(0, 4) : 'N/A';
            document.getElementById('modalRuntime').innerText = movie.runtime ? `${movie.runtime} mnt` : '';
            
            // Poster and Backdrop
            const posterPath = movie.poster_path ? `${IMG_URL}${movie.poster_path}` : 'https://via.placeholder.com/300x450?text=No+Poster';
            const backdropPath = movie.backdrop_path ? `https://image.themoviedb.org/t/p/original${movie.backdrop_path}` : '';
            
            document.getElementById('modalPoster').src = posterPath;
            document.getElementById('modalBackdrop').style.backgroundImage = backdropPath ? `url('${backdropPath}')` : '';
            
            // Genres
            const genresContainer = document.getElementById('modalGenres');
            if (genresContainer) {
                genresContainer.innerHTML = movie.genres.map(g => `<span class="modal-genre-tag">${g.name}</span>`).join('');
            }
            
            // Cast
            const castContainer = document.getElementById('modalCast');
            if (castContainer && movie.credits && movie.credits.cast) {
                const topCast = movie.credits.cast.slice(0, 10);
                castContainer.innerHTML = topCast.map(actor => {
                    const profileImg = actor.profile_path ? `${IMG_URL}${actor.profile_path}` : 'https://via.placeholder.com/150?text=No+Image';
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
            
            // Show modal
            const modal = document.getElementById('movieModal');
            if (modal) modal.classList.remove('hidden');
        })
        .catch(err => console.error('Error fetching movie details:', err));
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
        trailerPlayer.innerHTML = `<iframe src="https://www.youtube.com/embed/${currentTrailerKey}?autoplay=1" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
        trailerModal.classList.remove('hidden');
    }
}

function closeTrailer() {
    const trailerModal = document.getElementById('trailerModal');
    const trailerPlayer = document.getElementById('trailerPlayer');
    
    if (trailerModal && trailerPlayer) {
        trailerModal.classList.add('hidden');
        trailerPlayer.innerHTML = ''; // Stop video playing
    }
}

let currentPlayingId = null;

function playFullMovie() {
    if (!currentMovieId) return;
    currentPlayingId = currentMovieId;
    openVideoModal('vidsrc_cc');
}

function playFullMovieHero() {
    if (!window.heroMovieId) return;
    currentPlayingId = window.heroMovieId;
    openVideoModal('vidsrc_cc');
}

function switchServer(serverName) {
    // Update button active state
    const switcher = document.getElementById('serverSwitcher');
    if (switcher) {
        const buttons = switcher.querySelectorAll('button');
        buttons.forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
        });
        
        let targetIndex = 0;
        if (serverName === 'autoembed') targetIndex = 1;
        if (serverName === 'smashy') targetIndex = 2;
        
        buttons[targetIndex].classList.remove('btn-secondary');
        buttons[targetIndex].classList.add('btn-primary');
    }
    
    openVideoModal(serverName);
}

function openVideoModal(server) {
    if (!currentPlayingId) return;
    
    const trailerModal = document.getElementById('trailerModal');
    const trailerPlayer = document.getElementById('trailerPlayer');
    const serverSwitcher = document.getElementById('serverSwitcher');
    
    if (serverSwitcher) serverSwitcher.style.display = 'flex';
    
    let url = '';
    // Menggunakan sandbox super ketat untuk mematikan semua iklan pop-up saat diklik
    let sandboxRules = 'allow-same-origin allow-scripts allow-forms';
    
    if (server === 'vidsrc_cc') {
        url = `https://vidsrc.cc/v2/embed/movie/${currentPlayingId}`;
    } else if (server === 'autoembed') {
        url = `https://autoembed.co/movie/tmdb/${currentPlayingId}`;
    } else if (server === 'smashy') {
        url = `https://player.smashy.stream/movie/${currentPlayingId}`;
    }
    
    if (trailerModal && trailerPlayer) {
        trailerPlayer.innerHTML = `<iframe src="${url}" sandbox="${sandboxRules}" allow="autoplay; encrypted-media; fullscreen" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="width: 100%; height: 100%; border: none;"></iframe>`;
        trailerModal.classList.remove('hidden');
    }
}
