<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Dealer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TestDriveController;
use App\Http\Controllers\TradeInController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public routes - Phase 1
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer routes - Phase 2

    // Shopping cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'destroy'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Checkout & Orders
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

    // Test drives
    Route::get('/test-drives', [TestDriveController::class, 'index'])->name('test-drives.index');
    Route::post('/test-drives', [TestDriveController::class, 'store'])->name('test-drives.store');
    Route::patch('/test-drives/{testDrive}', [TestDriveController::class, 'update'])->name('test-drives.update');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::patch('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Trade-ins
    Route::get('/trade-ins', [TradeInController::class, 'index'])->name('trade-ins.index');
    Route::post('/trade-ins', [TradeInController::class, 'store'])->name('trade-ins.store');
    Route::get('/trade-ins/{tradeIn}', [TradeInController::class, 'show'])->name('trade-ins.show');
});

// Dealer routes - Phase 3
Route::prefix('dealer')->name('dealer.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [Dealer\DashboardController::class, 'index'])->name('dashboard');

    // Car inventory management
    Route::get('/cars', [Dealer\CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [Dealer\CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [Dealer\CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}', [Dealer\CarController::class, 'show'])->name('cars.show');
    Route::get('/cars/{car}/edit', [Dealer\CarController::class, 'edit'])->name('cars.edit');
    Route::patch('/cars/{car}', [Dealer\CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [Dealer\CarController::class, 'destroy'])->name('cars.destroy');

    // Orders
    Route::get('/orders', [Dealer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Dealer\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [Dealer\OrderController::class, 'update'])->name('orders.update');

    // Analytics
    Route::get('/analytics', [Dealer\AnalyticsController::class, 'index'])->name('analytics.index');

    // Commissions
    Route::get('/commissions', [Dealer\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{commission}', [Dealer\CommissionController::class, 'show'])->name('commissions.show');

    // Inquiries
    Route::get('/inquiries', [Dealer\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [Dealer\InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}', [Dealer\InquiryController::class, 'update'])->name('inquiries.update');

    // Test drives
    Route::get('/test-drives', [Dealer\TestDriveController::class, 'index'])->name('test-drives.index');
    Route::patch('/test-drives/{testDrive}', [Dealer\TestDriveController::class, 'update'])->name('test-drives.update');

    // Trade-ins
    Route::get('/trade-ins', [Dealer\TradeInController::class, 'index'])->name('trade-ins.index');
    Route::get('/trade-ins/{tradeIn}', [Dealer\TradeInController::class, 'show'])->name('trade-ins.show');
    Route::patch('/trade-ins/{tradeIn}', [Dealer\TradeInController::class, 'update'])->name('trade-ins.update');

    // Profile
    Route::get('/profile', [Dealer\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [Dealer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Dealer\ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes - Phase 4
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Brand management
    Route::get('/brands', [Admin\BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [Admin\BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [Admin\BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit', [Admin\BrandController::class, 'edit'])->name('brands.edit');
    Route::patch('/brands/{brand}', [Admin\BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [Admin\BrandController::class, 'destroy'])->name('brands.destroy');

    // Car model management
    Route::get('/car-models', [Admin\CarModelController::class, 'index'])->name('car-models.index');
    Route::get('/car-models/create', [Admin\CarModelController::class, 'create'])->name('car-models.create');
    Route::post('/car-models', [Admin\CarModelController::class, 'store'])->name('car-models.store');
    Route::get('/car-models/{carModel}/edit', [Admin\CarModelController::class, 'edit'])->name('car-models.edit');
    Route::patch('/car-models/{carModel}', [Admin\CarModelController::class, 'update'])->name('car-models.update');
    Route::delete('/car-models/{carModel}', [Admin\CarModelController::class, 'destroy'])->name('car-models.destroy');

    // Category management
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::patch('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Feature management
    Route::get('/features', [Admin\FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/create', [Admin\FeatureController::class, 'create'])->name('features.create');
    Route::post('/features', [Admin\FeatureController::class, 'store'])->name('features.store');
    Route::get('/features/{feature}/edit', [Admin\FeatureController::class, 'edit'])->name('features.edit');
    Route::patch('/features/{feature}', [Admin\FeatureController::class, 'update'])->name('features.update');
    Route::delete('/features/{feature}', [Admin\FeatureController::class, 'destroy'])->name('features.destroy');

    // Car management
    Route::get('/cars', [Admin\CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [Admin\CarController::class, 'show'])->name('cars.show');
    Route::patch('/cars/{car}', [Admin\CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [Admin\CarController::class, 'destroy'])->name('cars.destroy');

    // User management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Dealer management
    Route::get('/dealers', [Admin\DealerController::class, 'index'])->name('dealers.index');
    Route::get('/dealers/{dealer}', [Admin\DealerController::class, 'show'])->name('dealers.show');
    Route::patch('/dealers/{dealer}', [Admin\DealerController::class, 'update'])->name('dealers.update');

    // Order management
    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');

    // Review moderation
    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}', [Admin\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Inquiry management
    Route::get('/inquiries', [Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}', [Admin\InquiryController::class, 'update'])->name('inquiries.update');

    // Trade-in management
    Route::get('/trade-ins', [Admin\TradeInController::class, 'index'])->name('trade-ins.index');
    Route::get('/trade-ins/{tradeIn}', [Admin\TradeInController::class, 'show'])->name('trade-ins.show');
    Route::patch('/trade-ins/{tradeIn}', [Admin\TradeInController::class, 'update'])->name('trade-ins.update');

    // Commission management
    Route::get('/commissions', [Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{commission}', [Admin\CommissionController::class, 'show'])->name('commissions.show');
    Route::patch('/commissions/{commission}', [Admin\CommissionController::class, 'update'])->name('commissions.update');

    // Analytics
    Route::get('/analytics', [Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Settings
    Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
});

require __DIR__.'/auth.php';
