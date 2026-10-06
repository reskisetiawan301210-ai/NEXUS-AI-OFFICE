<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasLifeHub
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->hasLifeHub()) {
            if ($user->hasKasira()) {
                return redirect()->route('kasira.dashboard')->with('error', 'Akun Anda tidak memiliki akses ke fitur LifeHub AI.');
            }

            abort(403, 'Akses tidak diizinkan. Akun Anda tidak memiliki akses ke fitur LifeHub AI.');
        }

        return $next($request);
    }
}
