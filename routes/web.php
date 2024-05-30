<?php

use App\Models\Brands;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\DetailController;
use App\Http\Controllers\Front\LandingController;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\TypeController as AdminTypeController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\PaymentController;
use GuzzleHttp\Middleware;

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

Route::name('front.')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('index');
    Route::get('/detail/{slug}', [DetailController::class, 'index'])->name('detail');
     Route::group(['middleware' => 'auth'], function () {
        Route::get('/checkout/{slug}', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout/{slug}', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/payment/{bookingId}', [PaymentController::class, 'index'])->name('payment');
        Route::post('/payment/{bookingId}', [PaymentController::class, 'update'])->name('payment.update');
    });
});

Route::prefix('admin')->name('admin.')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin'
])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // brand
    Route::resource('brands', AdminBrandController::class);
    Route::post('/brands/{brand}/restore', [AdminBrandController::class, 'restore'])->name('brands.restore');
    Route::delete('/brands/{brand}/deletePermanent', [AdminBrandController::class, 'deletePermanent'])->name('brands.deletePermanent');
    //end brand
    //type
    Route::resource('/type', AdminTypeController::class);
    Route::post('/type/{type}/restore', [AdminTypeController::class, 'restore'])->name('type.restore');
    Route::delete('/type/{type}/deletePermanent', [AdminTypeController::class, 'deletePermanent'])->name('type.deletePermanent');
    //end type
    Route::resource('item', AdminItemController::class);
    Route::resource('/booking', AdminBookingController::class);
});
