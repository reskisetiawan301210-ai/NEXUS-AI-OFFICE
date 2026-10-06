<?php

namespace App\Http\Controllers\LifeHub;

use App\Http\Controllers\Controller;
use App\Models\MusicHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LifeHubController extends Controller
{
    /**
     * Display the LifeHub AI digital workspace dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Real, recorded activity only — no fabricated metrics.
        $recentTracks = MusicHistory::query()
            ->where('user_id', $user->id)
            ->orderByDesc('played_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get(['title', 'channel_title', 'played_at']);

        $musicHistoryCount = MusicHistory::query()->where('user_id', $user->id)->count();
        $musicFavoriteCount = $user->musicFavorites()->count();

        return view('lifehub.dashboard', [
            'user' => $user,
            'recentTracks' => $recentTracks,
            'musicHistoryCount' => $musicHistoryCount,
            'musicFavoriteCount' => $musicFavoriteCount,
        ]);
    }

    /**
     * Update user profile (Name & Avatar photo).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'avatar.image' => 'File harus berupa gambar yang valid.',
            'avatar.mimes' => 'Format foto profil harus JPG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto maksimal 5 MB.',
            'avatar.uploaded' => 'Foto profil gagal diunggah. Pastikan ukuran file tidak melebihi 5 MB.',
        ]);

        $avatarPath = $user->avatar_path;

        // 1. Remove old avatar if explicitly requested
        if ($request->boolean('remove_avatar') && $avatarPath) {
            if (Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = null;
        }

        // 2. Upload new avatar if provided
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = 'avatar_'.$user->id.'_'.time().'_'.Str::random(6).'.'.$extension;

            // Ensure directory exists in public storage
            Storage::disk('public')->makeDirectory('avatars');

            $newPath = $file->storeAs('avatars', $filename, 'public');

            if ($newPath) {
                // Delete previous avatar file only after new one has been safely stored
                if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                    Storage::disk('public')->delete($avatarPath);
                }
                $avatarPath = $newPath;
            }
        }

        $user->name = $validated['name'];
        $user->avatar_path = $avatarPath;
        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
