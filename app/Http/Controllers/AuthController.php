<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /** 
     * Method untuk registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|alpha|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.alpha' => 'Nama hanya boleh berisi huruf.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // Buat pengguna baru
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            // Logging error
            Log::error('Error saat registrasi: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_ip' => $request->ip(),
            ]);

            return back()->withErrors(['general' => 'Gagal registrasi. Silakan coba lagi.'])->withInput();
        }
    }

    /** 
     * Method untuk login pengguna.
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerasi sesi untuk keamanan
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        // Jika login gagal
        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    /** 
     * Method untuk logout pengguna.
     */
    public function logout(Request $request)
    {
        try {
            // Logout pengguna
            Auth::logout();

            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Anda telah logout.');
        } catch (\Exception $e) {
            // Logging error
            Log::error('Error saat logout: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'user_ip' => $request->ip(),
            ]);

            return redirect()->route('dashboard')->withErrors(['general' => 'Gagal logout. Silakan coba lagi.']);
        }
    }
}
