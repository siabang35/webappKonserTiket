<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ConcertController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminAuthController;

// Admin auth routes (public)
Route::group(['prefix' => 'admin', 'name' => 'admin.'], function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Protected admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth:admin'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

     // Profile management
     Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
     Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Concert management
    Route::resource('concerts', ConcertController::class);

    // Order management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
