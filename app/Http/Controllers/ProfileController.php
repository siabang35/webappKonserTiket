<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('profiles.profile', compact('user')); // Pastikan file view 'profiles/profile.blade.php' tersedia
    }

    /**
     * Menampilkan halaman edit profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user')); // Pastikan file view 'profiles/edit.blade.php' tersedia
    }

    /**
     * Memperbarui profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed', // Tambahan validasi untuk password opsional
        ]);

        // Update data pengguna
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Hanya hash password jika diubah
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Simpan perubahan
        $user->save();

        // Logout pengguna jika password diubah
        if ($request->filled('password')) {
            Auth::logout(); // Logout pengguna setelah update password
            return redirect()->route('login')->with('success', 'Profil berhasil diperbarui, silakan login ulang.');
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
