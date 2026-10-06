<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'youtube_id',
        'title',
        'channel_title',
        'thumbnail_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
