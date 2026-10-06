<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'avatar_path', 'password', 'username', 'google_id', 'product', 'has_lifehub', 'has_kasira'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_lifehub' => 'boolean',
            'has_kasira' => 'boolean',
        ];
    }

    public function isKasira(): bool
    {
        return $this->hasKasira() && ! $this->hasLifeHub();
    }

    public function isLifeHub(): bool
    {
        return $this->hasLifeHub() && ! $this->hasKasira();
    }

    public function hasLifeHub(): bool
    {
        if ($this->has_lifehub !== null) {
            return (bool) $this->has_lifehub;
        }

        return (bool) ($this->product === 'lifehub' || $this->product === 'both');
    }

    public function hasKasira(): bool
    {
        if ($this->has_kasira !== null) {
            return (bool) $this->has_kasira;
        }

        return (bool) ($this->product === 'kasira' || $this->product === 'both');
    }

    public function hasBothFeatures(): bool
    {
        return $this->hasLifeHub() && $this->hasKasira();
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function business(): HasOne
    {
        return $this->hasOne(Business::class)->latestOfMany();
    }

    /**
     * Get the active business for the user.
     */
    public function currentBusiness(): ?Business
    {
        return $this->business;
    }

    /**
     * Get avatar image URL or fallback to dynamic initial avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return asset('storage/'.$this->avatar_path);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name ?? 'User').'&background=EDE9FE&color=6D28D9&bold=true&size=256';
    }

    /**
     * @return HasMany<MusicPlaylist, $this>
     */
    public function musicPlaylists(): HasMany
    {
        return $this->hasMany(MusicPlaylist::class);
    }

    /**
     * @return HasMany<MusicHistory, $this>
     */
    public function musicHistory(): HasMany
    {
        return $this->hasMany(MusicHistory::class);
    }

    /**
     * @return HasMany<MusicFavorite, $this>
     */
    public function musicFavorites(): HasMany
    {
        return $this->hasMany(MusicFavorite::class);
    }
}
