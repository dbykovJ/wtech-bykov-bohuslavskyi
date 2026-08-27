<?php

use App\Http\Controllers\Account\LoyaltyController;
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
use App\Http\Controllers\Newsletter\NewsletterController;
use App\Http\Controllers\Payment\LiqPayWebhookController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\User\UserController;
use App\Services\Loyalty\LoyaltyService;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// Public pages
Route::get('/', fn() => view('home', [
    'productsOnSale' => ProductService::getOnSale(),
    'newArrivals' => ProductService::getNewArrivals(),
]))->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('category');

Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('product');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/cart', [CartItemController::class, 'accountCart'])->name('cart');

Route::post('/cart/promo', [CartItemController::class, 'applyPromo'])->name('cart.promo.apply');
Route::delete('/cart/promo', [CartItemController::class, 'removePromo'])->name('cart.promo.remove');

Route::post('/cart/add', [CartItemController::class, 'add']);
Route::patch('/cart/{cartItemId}', [CartItemController::class, 'update']);
Route::delete('/cart/{cartItemId}', [CartItemController::class, 'remove']);
Route::delete('/cart', [CartItemController::class, 'clear']);

Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
Route::post('/payment', [CheckoutController::class, 'pay'])->name('payment.pay');
Route::post('/liqpay/webhook', [LiqPayWebhookController::class, 'handle'])->name('liqpay.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/account', fn(LoyaltyService $loyalty) => view('account.index', [
        'loyaltyProgress' => $loyalty->getProgress(Auth::user()),
    ]))->name('account');
    Route::get('/account/personal-data', [UserController::class, 'show'])->name('account.personal-data');
    Route::put('/account/personal-data', [UserController::class, 'update'])->name('account.personal-data.update');
    Route::get('/account/cart', [CartItemController::class, 'accountCart'])->name('account.cart');
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::post('/account/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('account.orders.reorder');
    Route::post('/account/password', [PasswordController::class, 'update'])->name('account.password.update');
    Route::post('/account/loyalty/create', [LoyaltyController::class, 'create'])->name('account.loyalty.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Cart & checkout flow
Route::get('/delivery', fn() => view('delivery'))->name('delivery');
Route::get('/checkout/personal-data', [CheckoutController::class, 'personalData'])->name('checkout.personal-data');
Route::post('/checkout/personal-data', [CheckoutController::class, 'storePersonalData'])->name('checkout.personal-data.store');
Route::get('/order-confirm', [CheckoutController::class, 'confirm'])->name('order-confirm');

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

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/shipping-label', [AdminOrderController::class, 'shippingLabel'])->name('orders.shipping-label');
});
