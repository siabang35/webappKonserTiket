<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Jika pengguna sudah login, redirect ke dashboard
            return redirect()->route('dashboard');
        }

        // Jika pengguna belum login, lanjutkan ke rute berikutnya
        return $next($request);
    }
}
