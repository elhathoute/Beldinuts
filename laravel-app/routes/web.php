<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeViewPath']
], function() {
    // Home page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Order page
    Route::get('/commander', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/{order}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
    Route::post('/order/{order}/generate-invoice', [OrderController::class, 'generateInvoiceFromView'])->name('order.generateInvoice');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/search', [OrderController::class, 'searchByPhone'])->name('orders.search');
    
    // Admin routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('products', AdminController::class);
        Route::post('/products/{product}/photos', [AdminController::class, 'storePhoto'])->name('products.photos.store');
        Route::delete('/products/{product}/photos/{photo}', [AdminController::class, 'deletePhoto'])->name('products.photos.destroy');
        Route::put('/products/{product}/photos/{photo}/order', [AdminController::class, 'updatePhotoOrder'])->name('products.photos.order');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::put('/notifications/{notification}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::put('/notifications/read-all', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.readAll');
    });
    
    // Authentication routes
    require __DIR__.'/auth.php';
});
