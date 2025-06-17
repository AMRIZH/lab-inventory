<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class EnsureSupabaseToken
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('supabase_token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
// This middleware checks if the Supabase token exists in the session.
// If not, it redirects the user to the login page.