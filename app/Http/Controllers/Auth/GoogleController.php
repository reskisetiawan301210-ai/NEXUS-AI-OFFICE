<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors([
                'login' => __('Gagal masuk dengan Google. Silakan coba lagi.'),
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        $isNewUser = false;

        if ($user) {
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }
        } else {
            $isNewUser = true;
            $baseUsername = Str::slug($googleUser->getNickname() ?: explode('@', $googleUser->getEmail())[0], '_');
            $username = $baseUsername;
            $counter = 1;

            while (User::where('username', $username)->exists()) {
                $username = "{$baseUsername}_{$counter}";
                $counter++;
            }

            $user = User::create([
                'name' => $googleUser->getName() ?: $username,
                'username' => $username,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null,
                'product' => null,
                'has_kasira' => false,
                'has_lifehub' => false,
            ]);
        }

        Auth::login($user, true);

        request()->session()->regenerate();
        session(['has_account' => true]);
        cookie()->queue(cookie()->forever('lifehub_has_account', '1'));

        // If user does not have product chosen yet, ask them to choose
        if (empty($user->product)) {
            return redirect()->route('auth.google.features');
        }

        if ($user->isKasira()) {
            return redirect()->intended(route('kasira.dashboard'));
        }

        return redirect()->intended(route('lifehub.dashboard'));
    }

    public function showFeatureSelection(): View|RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        // If already selected, do not ask again
        if (! empty($user->product)) {
            return $user->isKasira()
                ? redirect()->route('kasira.dashboard')
                : redirect()->route('lifehub.dashboard');
        }

        return view('auth.google-features', [
            'user' => $user,
        ]);
    }

    public function saveFeatures(Request $request): RedirectResponse
    {
        $chosenProduct = $request->input('product', $request->input('feature'));
        $request->merge(['product' => $chosenProduct]);

        $validated = $request->validate([
            'product' => ['required', 'string', 'in:kasira,lifehub'],
        ], [
            'product.required' => 'Silakan pilih produk yang ingin Anda gunakan (Kasira atau LifeHub AI).',
            'product.in' => 'Pilihan produk tidak valid. Pilih Kasira atau LifeHub AI.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $isKasira = $validated['product'] === 'kasira';

        $user->update([
            'product' => $validated['product'],
            'has_kasira' => $isKasira,
            'has_lifehub' => ! $isKasira,
        ]);

        if ($user->isKasira()) {
            return redirect()->route('kasira.dashboard');
        }

        return redirect()->route('lifehub.dashboard');
    }
}
