<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user watchlist
     */
    public function index()
    {
        $watchlist = auth()->user()->watchlists()->paginate(12);
        $stats = [
            'total' => auth()->user()->watchlists()->count(),
            'watching' => auth()->user()->watchlists()->where('status', 'watching')->count(),
            'watched' => auth()->user()->watchlists()->where('status', 'watched')->count(),
            'planning' => auth()->user()->watchlists()->where('status', 'planning')->count(),
        ];

        return view('watchlist.index', compact('watchlist', 'stats'));
    }

    /**
     * Add movie to watchlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|integer',
            'title' => 'required|string',
            'overview' => 'nullable|string',
            'poster_path' => 'nullable|string',
            'vote_average' => 'nullable|numeric',
            'status' => 'required|in:watching,watched,planning',
        ]);

        $watchlist = Watchlist::updateOrCreate(
            ['user_id' => auth()->id(), 'movie_id' => $validated['movie_id']],
            [
                'title' => $validated['title'],
                'overview' => $validated['overview'],
                'poster_path' => $validated['poster_path'],
                'vote_average' => $validated['vote_average'],
                'status' => $validated['status'],
            ]
        );

        return response()->json([
            'message' => 'Film ditambahkan ke watchlist!',
            'watchlist' => $watchlist,
        ]);
    }

    /**
     * Update watchlist status
     */
    public function update(Request $request, $movieId)
    {
        $validated = $request->validate([
            'status' => 'required|in:watching,watched,planning',
        ]);

        $watchlist = Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->firstOrFail();

        $watchlist->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Status diperbarui!', 'watchlist' => $watchlist]);
    }

    /**
     * Remove from watchlist
     */
    public function destroy($movieId)
    {
        Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->delete();

        return response()->json(['message' => 'Film dihapus dari watchlist!']);
    }

    /**
     * Get watchlist by status
     */
    public function byStatus($status)
    {
        $watchlist = auth()->user()->watchlists()
            ->where('status', $status)
            ->paginate(12);

        return view('watchlist.index', [
            'watchlist' => $watchlist,
            'stats' => [
                'total' => auth()->user()->watchlists()->count(),
                'watching' => auth()->user()->watchlists()->where('status', 'watching')->count(),
                'watched' => auth()->user()->watchlists()->where('status', 'watched')->count(),
                'planning' => auth()->user()->watchlists()->where('status', 'planning')->count(),
            ],
            'activeStatus' => $status,
        ]);
    }
}
