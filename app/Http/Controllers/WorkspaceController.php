<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    /**
     * Show the workspace selector for users with multiple platforms.
     */
    public function select(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // If user only has one feature, direct them immediately
        if ($user->hasKasira() && ! $user->hasLifeHub()) {
            return redirect()->route('kasira.dashboard');
        }

        if ($user->hasLifeHub() && ! $user->hasKasira()) {
            return redirect()->route('lifehub.dashboard');
        }

        return view('workspace.select', [
            'user' => $user,
        ]);
    }
}
