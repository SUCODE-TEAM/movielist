<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'overview',
        'poster_path',
        'vote_average',
        'status',
    ];

    /**
     * Get the user that owns the watchlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
