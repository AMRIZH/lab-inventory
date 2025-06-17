<?php

use Illuminate\Support\Facades\Route;
use App\Services\SupabaseService;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemUnitController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuthController;

// Health Check Route (for Railway and monitoring)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'app' => config('app.name'),
        'version' => '1.0.0'
    ]);
});

// Authentication Routes

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Confirmation Routes (public)
Route::get('/auth/confirm', [AuthController::class, 'confirmEmail'])->name('auth.confirm');
Route::post('/auth/confirm', [AuthController::class, 'processEmailConfirmation']);
Route::get('/auth/callback', [AuthController::class, 'authCallback'])->name('auth.callback');

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lab Routes
    Route::get('/labs/{slug}', [LabController::class, 'show'])->name('labs.show');

    // Item Routes
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Item Units Routes
    Route::post('/items/{item}/units', [ItemController::class, 'storeUnits'])->name('items.units.store');
    Route::post('/item-units', [ItemUnitController::class, 'store'])->name('item-units.store');
    Route::put('/item-units/mass-update', [ItemUnitController::class, 'massUpdate'])->name('item-units.mass-update');
    Route::delete('/item-units/{id}', [ItemUnitController::class, 'destroy'])->name('item-units.destroy');

    // Product Routes
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Export Routes
    Route::get('/export', [ExportController::class, 'export'])->name('export.inventaris');
});
