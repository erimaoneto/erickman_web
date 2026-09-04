<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\FleetController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\EmailController;

// --- FRONT-END PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan/{slug}', [HomeController::class, 'serviceDetail'])->name('service.detail');
Route::post('/kirim-pesan', [HomeController::class, 'submitInquiry'])->name('inquiry.submit');

// --- AUTHENTICATION ROUTES ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ADMIN PANEL ROUTES (PROTECTED) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Content Management (CMS)
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
        Route::post('/settings', [ContentController::class, 'updateSettings'])->name('settings.update');

        Route::get('/banners', [ContentController::class, 'banners'])->name('banners');
        Route::post('/banners', [ContentController::class, 'storeBanner'])->name('banners.store');
        Route::put('/banners/{id}', [ContentController::class, 'updateBanner'])->name('banners.update');
        Route::delete('/banners/{id}', [ContentController::class, 'deleteBanner'])->name('banners.delete');

        Route::get('/services', [ContentController::class, 'services'])->name('services');
        Route::get('/services/create', [ContentController::class, 'createService'])->name('services.create');
        Route::post('/services', [ContentController::class, 'storeService'])->name('services.store');
        Route::get('/services/{id}/edit', [ContentController::class, 'editService'])->name('services.edit');
        Route::put('/services/{id}', [ContentController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{id}', [ContentController::class, 'deleteService'])->name('services.delete');
    });

    // Fleet Dashboard & Management
    Route::resource('fleets', FleetController::class);
    Route::post('/fleets/{id}/maintenance', [FleetController::class, 'storeMaintenance'])->name('fleets.maintenance.store');
    Route::delete('/maintenance/{id}', [FleetController::class, 'deleteMaintenance'])->name('fleets.maintenance.delete');

    // Finance Dashboard & Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/create', [FinanceController::class, 'create'])->name('create');
        Route::post('/', [FinanceController::class, 'store'])->name('store');
        Route::delete('/{id}', [FinanceController::class, 'destroy'])->name('destroy');
        Route::get('/report', [FinanceController::class, 'report'])->name('report');
    });

    // Email Management (Inbox, Compose, Outbox)
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/inbox', [EmailController::class, 'inbox'])->name('inbox');
        Route::get('/inbox/{id}', [EmailController::class, 'showInquiry'])->name('show');
        Route::delete('/inbox/{id}', [EmailController::class, 'deleteInquiry'])->name('delete');
        Route::get('/compose', [EmailController::class, 'compose'])->name('compose');
        Route::post('/send', [EmailController::class, 'sendEmail'])->name('send');
        Route::get('/outbox', [EmailController::class, 'outbox'])->name('outbox');
    });
});
