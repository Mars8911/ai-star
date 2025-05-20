<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\AccountController;

Route::middleware(['admin'])->group(function () {
    // 儀表板
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 會員管理
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/{user}/toggle-blacklist', [UserController::class, 'toggleBlacklist'])->name('users.toggle-blacklist');
        Route::get('/{user}/export', [UserController::class, 'export'])->name('users.export');
    });

    // 訂單管理
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/export', [OrderController::class, 'export'])->name('orders.export');
    });

    // 頁面管理
    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('pages.index');
        Route::get('/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::post('/update-order', [PageController::class, 'updateOrder'])->name('pages.update-order');
    });

    // 訊息管理
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/{message}/update-status', [MessageController::class, 'updateStatus'])->name('messages.update-status');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/export', [MessageController::class, 'export'])->name('messages.export');
    });

    // 圖像管理
    Route::prefix('images')->group(function () {
        Route::get('/', [ImageController::class, 'index'])->name('images.index');
        Route::get('/create', [ImageController::class, 'create'])->name('images.create');
        Route::post('/', [ImageController::class, 'store'])->name('images.store');
        Route::get('/{image}/edit', [ImageController::class, 'edit'])->name('images.edit');
        Route::put('/{image}', [ImageController::class, 'update'])->name('images.update');
        Route::delete('/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    });

    // 帳號管理
    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('/{admin}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::put('/{admin}', [AccountController::class, 'update'])->name('accounts.update');
        Route::post('/{admin}/toggle-status', [AccountController::class, 'toggleStatus'])->name('accounts.toggle-status');
        Route::delete('/{admin}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    });
}); 