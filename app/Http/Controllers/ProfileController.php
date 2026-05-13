<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user profile
     */
    public function show()
    {
        $user = auth()->user();
        $stats = [
            'watchlist_count' => $user->watchlists()->count(),
            'ratings_count' => $user->ratings()->count(),
            'watched_count' => $user->watchlists()->where('status', 'watched')->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil diperbarui!');
    }
}
