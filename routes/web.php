<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('pages.dashboard');
// });

Route::middleware(['isGuest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login'); 
    Route::post('/logins', [AuthController::class, 'login'])->name('auth.login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('isLogin');

Route::middleware(['isLogin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/export-profit', [DashboardController::class, 'exportExcel'])->name('dashboard.exportProfit');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('delete');
        Route::get('/export', [ProductController::class, 'exportExcel'])->name('exportProducts');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/export', [UserController::class, 'exportExcel'])->name('exportUsers');
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/product-sale', [SaleController::class, 'productSale'])->name('productSale');
        Route::post('/checkout', [SaleController::class, 'checkout'])->name('checkout');
        Route::post('/payment-transaction', [SaleController::class, 'paymentTransaction'])->name('payment');
        Route::post('/member-transaction', [SaleController::class, 'memberTransaction'])->name('memberpayment');
        Route::get('/receipt/{id}', [SaleController::class, 'showReceipt'])->name('receipt');

        Route::post('/invoice/{Id}', [SaleController::class, 'printPDF'])->name('invoice');
        Route::get('/export', [SaleController::class, 'exportExcel'])->name('exportInvoice');
    });
});