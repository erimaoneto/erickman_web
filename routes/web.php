<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\FleetController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\UserController;

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

    // Admin root entry point: smart redirect based on user role
    Route::get('/', function () {
        if (auth()->user()->role === 'keuangan') {
            return redirect()->route('admin.finance.index');
        }
        return redirect()->route('admin.dashboard');
    });

    // Finance Dashboard & Management (Accessible by superadmin and keuangan)
    Route::middleware(['role:superadmin,keuangan'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/create', [FinanceController::class, 'create'])->name('create');
        Route::post('/', [FinanceController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FinanceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FinanceController::class, 'update'])->name('update');
        Route::delete('/{id}', [FinanceController::class, 'destroy'])->name('destroy');
        Route::get('/report', [FinanceController::class, 'report'])->name('report');
    });

    // Modules Restricted Exclusively to Superadmin
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

            // Navigation Menus
            Route::get('/menus', [ContentController::class, 'menus'])->name('menus');
            Route::post('/menus', [ContentController::class, 'storeMenu'])->name('menus.store');
            Route::put('/menus/{id}', [ContentController::class, 'updateMenu'])->name('menus.update');
            Route::delete('/menus/{id}', [ContentController::class, 'deleteMenu'])->name('menus.delete');

            // Key Metrics
            Route::get('/metrics', [ContentController::class, 'metrics'])->name('metrics');
            Route::post('/metrics', [ContentController::class, 'storeMetric'])->name('metrics.store');
            Route::put('/metrics/{id}', [ContentController::class, 'updateMetric'])->name('metrics.update');
            Route::delete('/metrics/{id}', [ContentController::class, 'deleteMetric'])->name('metrics.delete');

            // About Pillars
            Route::get('/pillars', [ContentController::class, 'pillars'])->name('pillars');
            Route::post('/pillars', [ContentController::class, 'storePillar'])->name('pillars.store');
            Route::put('/pillars/{id}', [ContentController::class, 'updatePillar'])->name('pillars.update');
            Route::delete('/pillars/{id}', [ContentController::class, 'deletePillar'])->name('pillars.delete');

            // HSE Items
            Route::get('/hse', [ContentController::class, 'hse'])->name('hse');
            Route::post('/hse', [ContentController::class, 'storeHse'])->name('hse.store');
            Route::put('/hse/{id}', [ContentController::class, 'updateHse'])->name('hse.update');
            Route::delete('/hse/{id}', [ContentController::class, 'deleteHse'])->name('hse.delete');

            // Partners
            Route::get('/partners', [ContentController::class, 'partners'])->name('partners');
            Route::post('/partners', [ContentController::class, 'storePartner'])->name('partners.store');
            Route::put('/partners/{id}', [ContentController::class, 'updatePartner'])->name('partners.update');
            Route::delete('/partners/{id}', [ContentController::class, 'deletePartner'])->name('partners.delete');
        });

        // Fleet Dashboard & Management
        Route::resource('fleets', FleetController::class);
        Route::post('/fleets/{id}/maintenance', [FleetController::class, 'storeMaintenance'])->name('fleets.maintenance.store');
        Route::delete('/maintenance/{id}', [FleetController::class, 'deleteMaintenance'])->name('fleets.maintenance.delete');

        // Email Management (Inbox, Compose, Outbox)
        Route::prefix('email')->name('email.')->group(function () {
            Route::get('/inbox', [EmailController::class, 'inbox'])->name('inbox');
            Route::get('/inbox/{id}', [EmailController::class, 'showInquiry'])->name('show');
            Route::delete('/inbox/{id}', [EmailController::class, 'deleteInquiry'])->name('delete');
            Route::get('/compose', [EmailController::class, 'compose'])->name('compose');
            Route::post('/send', [EmailController::class, 'sendEmail'])->name('send');
            Route::get('/outbox', [EmailController::class, 'outbox'])->name('outbox');
        });

        // User Management (Kelola Pengguna)
        Route::resource('users', UserController::class)->except(['show']);
    });
});
