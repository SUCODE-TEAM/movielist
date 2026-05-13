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
            alert(`Anda memilih film: ${movie.title}\nSinopsis: ${movie.overview}`);
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
