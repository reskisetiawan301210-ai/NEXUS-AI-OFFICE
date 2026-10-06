<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request): View
    {
        $returningUser = $request->hasCookie('lifehub_has_account') || session()->has('has_account');

        return view('auth.register', [
            'returningUser' => $returningUser,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        // Accept either 'product' or 'feature' parameter from form
        $chosenProduct = $request->input('product', $request->input('feature'));
        $request->merge(['product' => $chosenProduct]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'alpha_dash', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'product' => ['required', 'string', 'in:kasira,lifehub,both'],
        ], [
            'product.required' => 'Silakan pilih produk yang ingin kamu gunakan (Kasira atau LifeHub AI).',
            'product.in' => 'Pilihan produk tidak valid. Pilih Kasira atau LifeHub AI.',
        ]);

        $username = $validated['username'] ?? null;
        if (! $username) {
            $baseUsername = Str::slug(explode('@', $validated['email'])[0], '_');
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = "{$baseUsername}_{$counter}";
                $counter++;
            }
        } else {
            $username = strtolower($username);
        }

        $hasKasira = in_array($validated['product'], ['kasira', 'both'], true);
        $hasLifeHub = in_array($validated['product'], ['lifehub', 'both'], true);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'product' => $validated['product'],
            'has_kasira' => $hasKasira,
            'has_lifehub' => $hasLifeHub,
        ]);

        Auth::login($user);

        $request->session()->regenerate();
        session(['has_account' => true]);
        cookie()->queue(cookie()->forever('lifehub_has_account', '1'));

        if ($user->hasBothFeatures()) {
            return redirect()->intended(route('workspace.select'));
        }

        if ($user->isKasira()) {
            return redirect()->intended(route('kasira.dashboard'));
        }

        return redirect()->intended(route('lifehub.dashboard'));
    }
}
