<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Staff\ProfileController as StaffProfile;

/*
| Public / Auth
*/
Route::get('/', function () {
    return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
| Admin routes (use auth; controllers enforce isAdmin())
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    // Orders (admin)
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/create', [AdminOrderController::class, 'create'])->name('admin.orders.create');
    Route::post('orders', [AdminOrderController::class, 'store'])->name('admin.orders.store');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // AJAX status update (admin only — controllers check admin)
    Route::post('orders/{order}/status-ajax', [AdminOrderController::class, 'ajaxUpdateStatus'])->name('admin.orders.updateStatus.ajax');

    // Products and Customers (admin)
    Route::resource('products', ProductController::class, ['as' => 'admin']);
    Route::resource('customers', CustomerController::class, ['as' => 'admin']);

    // Profile (admin)
    Route::get('profile', [AdminProfile::class, 'show'])->name('admin.profile.show');
    Route::put('profile', [AdminProfile::class, 'update'])->name('admin.profile.update');
});

/*
| Staff routes (use auth; controllers enforce isStaff())
*/
Route::prefix('staff')->middleware(['auth'])->group(function () {

    Route::get('/', [StaffDashboard::class, 'index'])->name('staff.dashboard');

    // Orders (staff)
    Route::get('orders', [StaffOrderController::class, 'index'])->name('staff.orders.index');
    Route::get('orders/create', [StaffOrderController::class, 'create'])->name('staff.orders.create');
    Route::post('orders', [StaffOrderController::class, 'store'])->name('staff.orders.store');
    Route::get('orders/{order}', [StaffOrderController::class, 'show'])->name('staff.orders.show');

    // Profile (staff)
    Route::get('profile', [StaffProfile::class, 'show'])->name('staff.profile.show');
    Route::put('profile', [StaffProfile::class, 'update'])->name('staff.profile.update');
});
