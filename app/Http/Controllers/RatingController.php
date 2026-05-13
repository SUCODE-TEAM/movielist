<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store or update rating
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:10',
            'review' => 'nullable|string|max:1000',
        ]);

        $rating = Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'movie_id' => $validated['movie_id']],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]
        );

        return response()->json([
            'message' => 'Rating disimpan!',
            'rating' => $rating,
        ]);
    }

    /**
     * Delete rating
     */
    public function destroy($movieId)
    {
        Rating::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->delete();

        return response()->json(['message' => 'Rating dihapus!']);
    }

    /**
     * Get user ratings
     */
    public function myRatings()
    {
        $ratings = auth()->user()->ratings()->paginate(12);

        return view('ratings.index', compact('ratings'));
    }
}
