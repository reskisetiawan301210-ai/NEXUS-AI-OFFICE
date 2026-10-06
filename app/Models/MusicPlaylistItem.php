<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicPlaylistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'music_playlist_id',
        'youtube_id',
        'title',
        'channel_title',
        'thumbnail_url',
        'duration',
        'order',
    ];

    public function playlist()
    {
        return $this->belongsTo(MusicPlaylist::class, 'music_playlist_id');
    }
}
