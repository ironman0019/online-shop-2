<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\Notify\SMSController;
use App\Http\Controllers\Admin\Content\FAQController;
use App\Http\Controllers\Admin\Content\MenuController;
use App\Http\Controllers\Admin\Content\PageController;
use App\Http\Controllers\Admin\Content\PostController;
use App\Http\Controllers\Admin\Market\BrandController;
use App\Http\Controllers\Admin\Market\OrderController;
use App\Http\Controllers\Admin\Notify\EmailController;
use App\Http\Controllers\Admin\Ticket\TicketController;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Market\CommentController;
use App\Http\Controllers\Admin\Market\GalleryController;
use App\Http\Controllers\Admin\Market\PaymentController;
use App\Http\Controllers\Admin\Market\ProductController;
use App\Http\Controllers\Admin\Market\StorageController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Admin\Market\CategoryController;
use App\Http\Controllers\Admin\Market\DeliveryController;
use App\Http\Controllers\Admin\Market\DiscountController;
use App\Http\Controllers\Admin\Market\PropertyController;
use App\Http\Controllers\Admin\Content\CommentController as ContentCommentController;
use App\Http\Controllers\Admin\Content\CategoryController as ContentCategoryController;
use App\Http\Controllers\Admin\Setting\SettingController;

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

    // Content routes
    Route::prefix('content')->name('content.')->group(function() {
        Route::resource('category', ContentCategoryController::class);
        Route::resource('comment', ContentCommentController::class);
        Route::resource('faq', FAQController::class);
        Route::resource('menu', MenuController::class);
        Route::resource('page', PageController::class);
        Route::resource('post', PostController::class);
    });


    // User routes
    Route::prefix('user')->name('user.')->group(function() {
        Route::resource('admin-user', AdminUserController::class);
        Route::resource('customer', CustomerController::class);
        Route::resource('role', RoleController::class);
    });


    // Notify routes
    Route::prefix('notify')->name('notify.')->group(function() {
        Route::resource('email', EmailController::class);
        Route::resource('sms', SMSController::class);
    });


    // Tickets routes
    Route::prefix('tickets')->name('tickets.')->group(function() {
        Route::get('/new-tickets', [TicketController::class, 'newTickets'])->name('newTickets');
        Route::get('/open-tickets', [TicketController::class, 'openTickets'])->name('openTickets');
        Route::get('/closed-tickets', [TicketController::class, 'closedTickets'])->name('closedTickets');
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::get('/show/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::post('/store', [TicketController::class, 'store'])->name('store');
        Route::get('/edit/{ticket}', [TicketController::class, 'edit'])->name('edit');
        Route::put('/update/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/destroy/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
    });


    // Setting routes
    Route::prefix('setting')->name('setting.')->group(function() {
        Route::resource('setting', SettingController::class);
    });


});
