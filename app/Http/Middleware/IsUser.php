<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !str_contains(Auth::user()->email, '.admin@')) {
            return $next($request);
        }

        return redirect('/admin')->with('error', 'You are logged in as an admin. Please log out to access the client page.');
    }
}
