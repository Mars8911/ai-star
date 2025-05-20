<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 首頁
Route::get('/', [HomeController::class, 'index'])->name('home');

// 註冊與登入
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/register/personal', [RegisterController::class, 'showPersonalRegistrationForm'])->name('register.personal');
Route::get('/register/business', [RegisterController::class, 'showBusinessRegistrationForm'])->name('register.business');
Route::post('/register/personal', [RegisterController::class, 'registerPersonal'])->name('register.personal.submit');
Route::post('/register/business', [RegisterController::class, 'registerBusiness'])->name('register.business.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login/personal', [LoginController::class, 'showPersonalLoginForm'])->name('login.personal');
Route::get('/login/business', [LoginController::class, 'showBusinessLoginForm'])->name('login.business');
Route::post('/login/personal', [LoginController::class, 'loginPersonal'])->name('login.personal.submit');
Route::post('/login/business', [LoginController::class, 'loginBusiness'])->name('login.business.submit');

// 會員中心
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/personal', [DashboardController::class, 'personal'])->name('dashboard.personal');
    Route::get('/dashboard/business', [DashboardController::class, 'business'])->name('dashboard.business');
});

// 商品相關
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// 購買流程
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{id}', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/{id}/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/{id}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
});

// 通知
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
