<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ConcertController;
use App\Models\Concert;

// Rute untuk landing page
Route::get('/', function () {
    $concerts = Concert::all(); // Mengambil semua data konser dari database
    return view('landing', compact('concerts')); // Mengirimkan data konser ke view 'landing'
})->name('landing');

// Rute untuk tamu (guest)
Route::middleware('guest')->group(function () {
    // Halaman register
    Route::view('/register', 'register')->name('register');
    Route::post('/register', [AuthController::class, 'register']); // Proses registrasi

    // Halaman login
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']); // Proses login
});

// Rute untuk pengguna yang terautentikasi (auth)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $concerts = Concert::all(); // Menambahkan variabel $concerts agar view 'dashboard' dapat menggunakan data konser
        return view('dashboard', compact('concerts'));
    })->name('dashboard');

    // Pemesanan tiket
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Halaman konfirmasi pemesanan
    Route::view('/konfirmasi', 'konfirmasi')->name('order.konfirmasi'); // Pastikan file view 'konfirmasi.blade.php' sudah dibuat

    // Riwayat pemesanan tiket
    Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');

    // Rute logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute CRUD konser
    Route::resource('concerts', ConcertController::class);
});

// Rute tambahan untuk menanggapi 'home' yang tidak terdefinisi
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');
