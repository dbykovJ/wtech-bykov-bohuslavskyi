<?php

use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\CartItem\CartItemController;
use App\Http\Controllers\Cart\Checkout\CheckoutController;
use App\Http\Controllers\Cart\Shop\ShopController;
use App\Http\Controllers\Cart\Stripe\StripeWebhookController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\User\UserController;
use App\Services\Product\ProductService;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

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

Route::get('/cart', [CartItemController::class, 'accountCart'])->name('cart');

Route::post('/cart/promo', [CartItemController::class, 'applyPromo'])->name('cart.promo.apply');
Route::delete('/cart/promo', [CartItemController::class, 'removePromo'])->name('cart.promo.remove');

Route::post('/cart/add', [CartItemController::class, 'add']);
Route::patch('/cart/{cartItemId}', [CartItemController::class, 'update']);
Route::delete('/cart/{cartItemId}', [CartItemController::class, 'remove']);
Route::delete('/cart', [CartItemController::class, 'clear']);

Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
Route::post('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::middleware('auth')->group(function () {
    Route::get('/account/cart', [CartItemController::class, 'accountCart'])->name('account.cart');
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::post('/account/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('account.orders.reorder');
    Route::post('/account/password', [PasswordController::class, 'update'])->name('account.password.update');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Cart & checkout flow
Route::get('/delivery', fn() => view('delivery'))->name('delivery');
Route::get('/checkout/personal-data', fn() => view('personal-data'))->name('checkout.personal-data');
Route::get('/order-confirm', [CheckoutController::class, 'confirm'])->name('order-confirm');
Route::get('/order-confirm', fn() => view('order-confirm'))->name('order-confirm');
Route::post('/webhooks/stripe', StripeWebhookController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('stripe.webhook');

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
Route::get('/account/personal-data', [UserController::class, 'show'])->name('account.personal-data');
Route::put('/account/personal-data', [UserController::class, 'update'])->name('account.personal-data.update');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
});
