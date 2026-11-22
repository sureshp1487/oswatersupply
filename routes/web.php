<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\SystemToolsController;
use App\Http\Controllers\Driver\DriverController;

// Public & Guest Routes
Route::get('/', function () {
    $products = \App\Models\Product::where('is_active', true)->orderBy('selling_price', 'asc')->get();
    return view('welcome', compact('products'));
})->name('landing');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/check-account', [AuthController::class, 'checkAccountAvailability'])->name('register.check_account');

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

    // Password Reset Flow
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Application Routes (Mobile-first)
Route::middleware(['auth', 'role:customer'])->prefix('app')->name('customer.')->group(function () {
    Route::get('/home', [CustomerController::class, 'home'])->name('home');
    Route::get('/services', [CustomerController::class, 'services'])->name('services');
    Route::get('/products', [CustomerController::class, 'products'])->name('products');
    Route::get('/cart', [CustomerController::class, 'bookWater'])->name('cart');
    Route::get('/book', [CustomerController::class, 'bookWater'])->name('book');
    Route::post('/order/place', [CustomerController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [CustomerController::class, 'showOrder'])->name('order.show');

    // Due & Payments
    Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
    Route::post('/payments/pay-order/{orderId}', [CustomerController::class, 'payOrderDue'])->name('pay.order');
    Route::post('/payments/pay-all-dues', [CustomerController::class, 'payAllDues'])->name('pay.all');
    Route::post('/payments/razorpay-order', [CustomerController::class, 'createRazorpayOrder'])->name('razorpay.order');

    // Profile & Addresses
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/address', [CustomerController::class, 'saveAddress'])->name('address.save');
    Route::post('/address/{id}/default', [CustomerController::class, 'setDefaultAddress'])->name('address.default');
    Route::delete('/address/{id}', [CustomerController::class, 'deleteAddress'])->name('address.delete');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/orders/{id}/assign-driver', [AdminController::class, 'assignDriver'])->name('orders.assign_driver');
    Route::post('/orders/{id}/record-payment', [AdminController::class, 'recordOfflinePayment'])->name('orders.record_payment');

    // Dues Management Module
    Route::get('/dues', [AdminController::class, 'dues'])->name('dues.index');
    Route::post('/dues/{userId}/settle', [AdminController::class, 'settleCustomerDue'])->name('dues.settle');

    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
    Route::get('/customers/{id}', [AdminController::class, 'customerDetail'])->name('customers.detail');
    Route::post('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminController::class, 'deleteCustomer'])->name('customers.destroy');

    // Deliveries
    Route::get('/deliveries', [AdminController::class, 'deliveries'])->name('deliveries.index');

    // System Staff Users (Admin & Delivery Drivers)
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Delivery Time Slots
    Route::get('/slots', [AdminController::class, 'slots'])->name('slots.index');
    Route::post('/slots', [AdminController::class, 'storeSlot'])->name('slots.store');
    Route::post('/slots/{id}', [AdminController::class, 'updateSlot'])->name('slots.update');
    Route::delete('/slots/{id}', [AdminController::class, 'destroySlot'])->name('slots.destroy');

    // System Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // System Cache, Maintenance & Log Management
    Route::get('/system-tools', [SystemToolsController::class, 'index'])->name('system.index');
    Route::post('/system-tools/clear-cache', [SystemToolsController::class, 'clearCache'])->name('system.clear_cache');
    Route::post('/system-tools/read-log', [SystemToolsController::class, 'readLog'])->name('system.read_log');
    Route::post('/system-tools/rename-log', [SystemToolsController::class, 'renameLog'])->name('system.rename_log');

    // App & Dashboard Customization (Brand, Theme, Banner, Colors)
    Route::get('/customization', [AdminController::class, 'customization'])->name('customization.index');
    Route::post('/customization', [AdminController::class, 'updateCustomization'])->name('customization.update');

    // Hero Banners & Promotional Carousel Management
    Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::post('/banners/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');

    // Admin Real-Time Order Notifications & PWA Sync APIs
    Route::get('/notifications', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'unreadCount'])->name('notifications.unread_count');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark_all_read');
    Route::post('/notifications/push/subscribe', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'subscribePush'])->name('notifications.push.subscribe');
    Route::post('/notifications/push/unsubscribe', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'unsubscribePush'])->name('notifications.push.unsubscribe');
});

// Driver Mobile Application Routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/delivery/{assignmentId}/status', [DriverController::class, 'updateDeliveryStatus'])->name('delivery.status');
    Route::post('/delivery/{assignmentId}/razorpay-order', [DriverController::class, 'createRazorpayOrder'])->name('delivery.razorpay_order');
});
