<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', fn() => view('home'))->name('home');
Route::get('/shop', fn() => view('category'))->name('category');
Route::get('/product', fn() => view('product'))->name('product');
Route::get('/about', fn() => view('about'))->name('about');

// Cart & checkout flow
Route::get('/cart', fn() => view('cart'))->name('cart');
Route::get('/payment', fn() => view('payment'))->name('payment');
Route::get('/delivery', fn() => view('delivery'))->name('delivery');
Route::get('/checkout/personal-data', fn() => view('personal-data'))->name('checkout.personal-data');
Route::get('/order-confirm', fn() => view('order-confirm'))->name('order-confirm');

// Auth
Route::get('/login', fn() => view('auth.login'))
    ->middleware('guest')
    ->name('login');
Route::post('/login', LoginController::class)
    ->middleware('guest')
    ->name('login');
Route::get('/register', fn() => view('auth.register'))
    ->middleware('guest')
    ->name('register');
Route::post('/register', RegisterController::class)
    ->middleware('guest')
    ->name('register');

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

// Account
Route::get('/account', fn() => view('account.index'))->name('account');
Route::get('/account/personal-data', fn() => view('account.personal-data'))->name('account.personal-data');
Route::get('/account/cart', fn() => view('account.cart'))->name('account.cart');
Route::get('/account/orders', fn() => view('account.orders'))->name('account.orders');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');
    Route::resource('products', ProductController::class)->except('show');
    Route::get('/orders', fn() => view('admin.orders'))->name('orders');
});
