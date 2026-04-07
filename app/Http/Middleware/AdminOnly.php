<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            \Log::info('AdminOnly: not authenticated');
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->is_admin) {
            \Log::info('AdminOnly: user not admin', ['user_id' => $user->id, 'is_admin' => $user->is_admin]);
            Auth::logout();
            return redirect()->route('login')->withErrors(['error' => 'You do not have admin access.']);
        }

        return $next($request);
    }
}