<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    protected $apiKey;
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $imageBase = 'https://image.tmdb.org/t/p/w500';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    /**
     * Display all movies with categories
     */
    public function index(Request $request)
    {
        if (!$this->apiKey || $this->apiKey === 'your_tmdb_api_key_here') {
            return view('movies.index', [
                'trending' => [],
                'popular' => [],
                'nowPlaying' => [],
                'topRated' => [],
                'upcoming' => [],
                'error' => 'API Key TMDB belum diatur di file .env!'
            ]);
        }

        try {
            $trending = $this->getMovies('trending/movie/week');
            $popular = $this->getMovies('movie/popular');
            $nowPlaying = $this->getMovies('movie/now_playing');
            $topRated = $this->getMovies('movie/top_rated');
            $upcoming = $this->getMovies('movie/upcoming');

            // Add watchlist & rating info for authenticated user
            if (auth()->check()) {
                $userWatchlist = auth()->user()->watchlists->pluck('movie_id')->toArray();
                $userRatings = auth()->user()->ratings->keyBy('movie_id')->toArray();

                foreach ([$trending, $popular, $nowPlaying, $topRated, $upcoming] as &$movies) {
                    foreach ($movies as &$movie) {
                        $movie['in_watchlist'] = in_array($movie['id'], $userWatchlist);
                        $movie['user_rating'] = $userRatings[$movie['id']] ?? null;
                    }
                }
            }

            return view('movies.index', compact('trending', 'popular', 'nowPlaying', 'topRated', 'upcoming'));
        } catch (\Exception $e) {
            return view('movies.index', [
                'trending' => [],
                'popular' => [],
                'nowPlaying' => [],
                'topRated' => [],
                'upcoming' => [],
                'error' => 'Gagal mengambil data dari API: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show movie detail page
     */
    public function show($movieId)
    {
        try {
            $movie = $this->getMovieDetail($movieId);
            $credits = $this->getMovieCredits($movieId);
            $recommendations = $this->getMovieRecommendations($movieId);

            $inWatchlist = false;
            $userRating = null;
            $allRatings = [];

            if (auth()->check()) {
                $watchlist = Watchlist::where('user_id', auth()->id())
                    ->where('movie_id', $movieId)
                    ->first();
                $inWatchlist = $watchlist ? true : false;

                $userRatingObj = Rating::where('user_id', auth()->id())
                    ->where('movie_id', $movieId)
                    ->first();
                $userRating = $userRatingObj;

                $allRatings = Rating::where('movie_id', $movieId)->get();
            }

            return view('movies.show', compact('movie', 'credits', 'recommendations', 'inWatchlist', 'userRating', 'allRatings'));
        } catch (\Exception $e) {
            return redirect()->route('movies.index')->withErrors('Film tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Search movies
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (strlen($query) < 2) {
            return response()->json(['movies' => []]);
        }

        try {
            $response = Http::get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'page' => $request->input('page', 1),
            ]);

            $results = $response->json()['results'] ?? [];

            // Filter dan format results
            $movies = collect($results)
                ->filter(fn($m) => $m['poster_path'] !== null)
                ->map(function ($movie) {
                    return [
                        'id' => $movie['id'],
                        'title' => $movie['title'],
                        'poster_path' => $this->imageBase . $movie['poster_path'],
                        'release_date' => $movie['release_date'] ?? '',
                        'vote_average' => $movie['vote_average'] ?? 0,
                    ];
                })
                ->values()
                ->all();

            return response()->json(['movies' => $movies]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get movies by category
     */
    protected function getMovies($endpoint, $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/{$endpoint}", [
            'api_key' => $this->apiKey,
            'page' => $page,
        ]);

        return collect($response->json()['results'] ?? [])
            ->filter(fn($m) => $m['poster_path'] !== null)
            ->map(function ($movie) {
                return [
                    'id' => $movie['id'],
                    'title' => $movie['title'],
                    'overview' => $movie['overview'] ?? '',
                    'poster_path' => $this->imageBase . $movie['poster_path'],
                    'backdrop_path' => $this->imageBase . ($movie['backdrop_path'] ?? $movie['poster_path']),
                    'release_date' => $movie['release_date'] ?? '',
                    'vote_average' => $movie['vote_average'] ?? 0,
                ];
            })
            ->all();
    }

    /**
     * Get detailed movie information
     */
    protected function getMovieDetail($movieId)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$movieId}", [
            'api_key' => $this->apiKey,
        ]);

        $movie = $response->json();

        return [
            'id' => $movie['id'],
            'title' => $movie['title'],
            'overview' => $movie['overview'] ?? '',
            'poster_path' => $this->imageBase . $movie['poster_path'],
            'backdrop_path' => $this->imageBase . $movie['backdrop_path'],
            'release_date' => $movie['release_date'] ?? '',
            'runtime' => $movie['runtime'] ?? 0,
            'vote_average' => $movie['vote_average'] ?? 0,
            'vote_count' => $movie['vote_count'] ?? 0,
            'genres' => $movie['genres'] ?? [],
            'budget' => $movie['budget'] ?? 0,
            'revenue' => $movie['revenue'] ?? 0,
            'status' => $movie['status'] ?? '',
            'original_language' => $movie['original_language'] ?? '',
        ];
    }

    /**
     * Get movie credits
     */
    protected function getMovieCredits($movieId)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$movieId}/credits", [
            'api_key' => $this->apiKey,
        ]);

        $data = $response->json();

        return [
            'cast' => collect($data['cast'] ?? [])
                ->take(10)
                ->map(fn($actor) => [
                    'name' => $actor['name'],
                    'character' => $actor['character'] ?? '',
                    'profile_path' => $actor['profile_path'] ? $this->imageBase . $actor['profile_path'] : null,
                ])
                ->all(),
            'crew' => collect($data['crew'] ?? [])
                ->filter(fn($c) => in_array($c['department'], ['Directing', 'Writing']))
                ->take(5)
                ->map(fn($person) => [
                    'name' => $person['name'],
                    'job' => $person['job'],
                ])
                ->all(),
        ];
    }

    /**
     * Get recommended movies
     */
    protected function getMovieRecommendations($movieId)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$movieId}/recommendations", [
            'api_key' => $this->apiKey,
        ]);

        return collect($response->json()['results'] ?? [])
            ->filter(fn($m) => $m['poster_path'] !== null)
            ->take(8)
            ->map(function ($movie) {
                return [
                    'id' => $movie['id'],
                    'title' => $movie['title'],
                    'poster_path' => $this->imageBase . $movie['poster_path'],
                    'vote_average' => $movie['vote_average'] ?? 0,
                ];
            })
            ->all();
    }
}
