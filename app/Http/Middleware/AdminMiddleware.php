<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (!Auth::guard('admin')->check()) {
            // Jika request adalah AJAX, kembalikan response JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access required.'
                ], 401);
            }
            if (Auth::guard('admin')->user()->is_active === false) {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->withErrors(['error' => 'Akun Anda tidak aktif.']);
            }

            // Simpan intended URL sebelum redirect
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }

        // Pastikan user yang login memang memiliki role admin
        if (!Auth::guard('admin')->user()->isAdmin) {
            Auth::guard('admin')->logout();

            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
