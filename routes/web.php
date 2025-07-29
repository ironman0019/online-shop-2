<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Market\BrandController;
use App\Http\Controllers\Admin\Market\CategoryController;
use App\Http\Controllers\Admin\Market\CommentController;
use App\Http\Controllers\Admin\Market\DeliveryController;
use App\Http\Controllers\Admin\Market\DiscountController;
use App\Http\Controllers\Admin\Market\GalleryController;
use App\Http\Controllers\Admin\Market\OrderController;
use App\Http\Controllers\Admin\Market\PaymentController;
use App\Http\Controllers\Admin\Market\ProductController;
use App\Http\Controllers\Admin\Market\PropertyController;
use App\Http\Controllers\Admin\Market\StorageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('admin')->middleware([])->name('admin.')->group(function() {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Market routes
    Route::prefix('market')->name('market.')->group(function() {
        
        Route::resource('category', CategoryController::class);
        Route::resource('brand', BrandController::class);
        Route::resource('comment', CommentController::class);
        Route::resource('delivery', DeliveryController::class);
        Route::resource('product', ProductController::class);
        Route::resource('property', PropertyController::class);
        Route::resource('storage', StorageController::class);

        // Discount
        Route::prefix('discount')->group(function() {
            Route::get('/coupan', [DiscountController::class, 'coupan'])->name('discount.coupan');
            Route::get('/coupan/create', [DiscountController::class, 'coupanCreate'])->name('discount.coupan.create');
            Route::get('/common-discount', [DiscountController::class, 'commonDiscount'])->name('discount.commonDiscount');
            Route::get('/common-discount/create', [DiscountController::class, 'commonDiscountCreate'])->name('discount.commonDiscount.create');
            Route::get('/amazing-sale', [DiscountController::class, 'amazingSale'])->name('discount.amazingSale');
            Route::get('/amazing-sale/create', [DiscountController::class, 'amazingSaleCreate'])->name('discount.amazingSale.create');
        });


        // Order
        Route::prefix('order')->group(function() {
            Route::get('/', [OrderController::class, 'all'])->name('order.all');
            Route::get('/new-orders', [OrderController::class, 'newOrders'])->name('order.newOrders');
            Route::get('/sending', [OrderController::class, 'sending'])->name('order.sending');
            Route::get('/unpaid', [OrderController::class, 'unpaid'])->name('order.unpaid');
            Route::get('/canceled', [OrderController::class, 'canceled'])->name('order.canceled');
            Route::get('/returned', [OrderController::class, 'returned'])->name('order.returned');
            Route::get('show', [OrderController::class, 'show'])->name('order.show');
            Route::get('change-send-status', [OrderController::class, 'changeSendStatus'])->name('order.changeSendStatus');
            Route::get('change-order-status', [OrderController::class, 'changeOrderStatus'])->name('order.changeOrderStatus');
            Route::get('cancel-order', [OrderController::class, 'cancelOrder'])->name('order.cancelOrder');
        });


        // Payment
        Route::prefix('peyment')->group(function() {
            Route::get('/', [PaymentController::class, 'index'])->name('peyment.index');
            Route::get('/online', [PaymentController::class, 'online'])->name('peyment.online');
            Route::get('/offline', [PaymentController::class, 'offline'])->name('peyment.offline');
            Route::get('/attendance', [PaymentController::class, 'attendance'])->name('peyment.attendance');
            Route::get('/confirm', [PaymentController::class, 'confirm'])->name('peyment.confirm');
        });


        // Gallery
        Route::prefix('product')->group(function() {
            Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
            Route::post('/gallery/store', [GalleryController::class, 'store'])->name('gallery.store');
            Route::delete('/gallery/destroy', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        });


        // Storage
        Route::prefix('storage')->group(function() {
            Route::get('add/to-store', [StorageController::class, 'addToStore'])->name('storage.addToStore');
        });
        

    });


});
