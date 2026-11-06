<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\AdminController;
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
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Application Routes (Mobile-first)
Route::middleware(['auth', 'role:customer'])->prefix('app')->name('customer.')->group(function () {
    Route::get('/home', [CustomerController::class, 'home'])->name('home');
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

    // System Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // App & Dashboard Customization (Brand, Theme, Banner, Colors)
    Route::get('/customization', [AdminController::class, 'customization'])->name('customization.index');
    Route::post('/customization', [AdminController::class, 'updateCustomization'])->name('customization.update');
});

// Driver Mobile Application Routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/delivery/{assignmentId}/status', [DriverController::class, 'updateDeliveryStatus'])->name('delivery.status');
});
