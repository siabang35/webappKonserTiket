<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

// Rute untuk tamu (guest)
Route::middleware('guest')->group(function () {
    // Halaman register
    Route::view('/register', 'register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Halaman login
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk pengguna yang terautentikasi (auth)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('detail_konser'); // Pastikan file view 'detail_konser.blade.php' ada di folder resources/views.
    })->name('dashboard');

    // Pemesanan tiket
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Halaman konfirmasi
    Route::view('/konfirmasi', 'konfirmasi')->name('konfirmasi'); // Pastikan file view 'konfirmasi.blade.php' ada di folder resources/views.

    // Rute logout
    Route::post('/logout', function () {
        auth()->logout(); // Pastikan auth()->logout() bekerja sesuai konfigurasi Laravel Auth.
        return redirect('/login')->with('success', 'Anda telah logout.');
    })->name('logout');
});
