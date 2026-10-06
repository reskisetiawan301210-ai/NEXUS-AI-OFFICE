<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(Request $request): View
    {
        $returningUser = $request->hasCookie('lifehub_has_account') || session()->has('has_account') || Auth::check();

        return view('auth.login', [
            'returningUser' => $returningUser,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $validated['login'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            session(['has_account' => true]);
            cookie()->queue(cookie()->forever('lifehub_has_account', '1'));

            /** @var User $user */
            $user = Auth::user();

            if ($user->hasBothFeatures()) {
                return redirect()->intended(route('workspace.select'));
            }

            if ($user->isKasira()) {
                return redirect()->intended(route('kasira.dashboard'));
            }

            return redirect()->intended(route('lifehub.dashboard'));
        }

        return back()->withErrors([
            'login' => __('Kredensial yang diberikan tidak cocok dengan data kami.'),
        ])->onlyInput('login', 'remember');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        cookie()->queue(cookie()->forever('lifehub_has_account', '1'));

        return redirect()->route('login');
    }
}
