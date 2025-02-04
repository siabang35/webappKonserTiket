<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\PaymentController;
use App\Models\Concert;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTransferController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ConcertController as AdminConcertController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ArtistController;


// Rute untuk halaman landing
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Rute untuk tamu (guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk pengguna yang terautentikasi (auth)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
        Route::post('process/{order}', [PaymentController::class, 'process'])->middleware('auth')->name('process');
        Route::post('initiate', [PaymentController::class, 'initiatePayment']);
        Route::post('webhook', [PaymentController::class, 'handleWebhook']);
        Route::get('/success/{order}', [PaymentController::class, 'success'])->name('success');
        Route::get('/failed/{order}', [PaymentController::class, 'failed'])->name('failed');
    });



    // Rute profil pengguna
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/ticket/purchase/{concert}/{type}', [TicketController::class, 'purchase'])->name('ticket.purchase');

     // Ticket Management Routes
     Route::prefix('tickets')->name('tickets.')->group(function () {
        // View user's tickets
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');

        // Download ticket
        Route::get('/{ticket}/download', [TicketController::class, 'download'])->name('download');

        // Ticket transfer
        Route::post('/{ticket}/transfer/initiate', [TicketController::class, 'transfer'])
        ->name('transfer.initiate');

        Route::get('/transfer/{code}', [TicketTransferController::class, 'show'])->name('transfer.show');

        // Validate ticket
        Route::post('/{ticket}/validate', [TicketController::class, 'validate'])->name('validate');
    });

    // Pemesanan tiket
   // Pemesanan tiket
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/konfirmasi', [OrderController::class, 'konfirmasi'])->name('order.confirmation');

// Riwayat pesanan
Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');



    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{concert}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'notification'])->name('notifications.index');
    Route::post('/notification/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notification/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Help routes
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute CRUD konser
    Route::resource('concerts', ConcertController::class);
});

// Payment webhook/callback route (no auth required)
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');

//Route Admin Controller

Route::group(['prefix' => 'admin', 'name' => 'admin.'], function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
});

// Protected admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth:admin'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Profile management
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');




        Route::resource('artists', ArtistController::class);
    // Concert management
    Route::resource('concerts', AdminConcertController::class);
       // Admin Ticket Management
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [AdminTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/scan', [AdminTicketController::class, 'scan'])->name('scan');
        Route::get('/types', [AdminTicketController::class, 'ticketTypes'])->name('types.index');
        Route::post('/types', [AdminTicketController::class, 'storeTicketType'])->name('types.store');
        Route::put('/types/{ticketType}', [AdminTicketController::class, 'updateTicketType'])->name('types.update');
        Route::get('/stats', [AdminTicketController::class, 'stats'])->name('stats');
        Route::get('/export', [AdminTicketController::class, 'export'])->name('export');
    });
     // Order management
     Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
     Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
     Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Payment management
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('orders/{order}/refund', [AdminPaymentController::class, 'processRefund'])->name('payments.refund');
    Route::get('payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');
   // Public Ticket Validation (no auth required)
Route::get('/tickets/verify/{code}', [TicketController::class, 'verify'])
->name('tickets.verify');
    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Rute tambahan untuk mengarahkan 'home' ke dashboard
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');
