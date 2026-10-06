<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasKasira
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->hasKasira()) {
            if ($user->hasLifeHub()) {
                return redirect()->route('lifehub.dashboard')->with('error', 'Akun Anda tidak memiliki akses ke fitur Kasira.');
            }

            abort(403, 'Akses tidak diizinkan. Akun Anda tidak memiliki akses ke fitur Kasira.');
        }

        return $next($request);
    }
}
