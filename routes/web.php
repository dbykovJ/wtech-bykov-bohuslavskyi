<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Product\ProductController;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartItem\CartItemController;

$productService = new ProductService();
$productController = new ProductController($productService);


// Public pages
Route::get('/', fn() => view('home', [
    'productsOnSale' => $productService::getOnSale(),
    'newArrivals' => $productService::getNewArrivals(),
]))->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('category');

Route::get('/product/{id}', fn($id) => $productController->show($id))
    ->name('product');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');


Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartItemController::class, 'accountCart'])->name('cart');
    Route::post('/cart/add', [CartItemController::class, 'add']);
    Route::patch('/cart/{cartItemId}', [CartItemController::class, 'update']);
    Route::delete('/cart/{cartItemId}', [CartItemController::class, 'remove']);
    Route::delete('/cart', [CartItemController::class, 'clear']);
    Route::get('/account/cart', [CartItemController::class, 'accountCart'])->name('account.cart');
});

// Cart & checkout flow
Route::get('/payment', fn() => view('payment'))->name('payment');
Route::get('/delivery', fn() => view('delivery'))->name('delivery');
Route::get('/checkout/personal-data', fn() => view('personal-data'))->name('checkout.personal-data');
Route::get('/order-confirm', fn() => view('order-confirm'))->name('order-confirm');

// Auth
Route::get('/login', fn() => view('auth.login'))
    ->middleware('guest')
    ->name('login');
Route::post('/login', LoginController::class)
    ->middleware('guest');
Route::get('/register', fn() => view('auth.register'))
    ->middleware('guest')
    ->name('register');
Route::post('/register', RegisterController::class)
    ->middleware('guest');

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

// Account
Route::get('/account', fn() => view('account.index'))->name('account');
Route::get('/account/personal-data', fn() => view('account.personal-data'))->name('account.personal-data');
Route::get('/account/orders', fn() => view('account.orders'))->name('account.orders');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('/orders', fn() => view('admin.orders'))->name('orders');
});
