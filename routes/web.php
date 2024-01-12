<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\TypeController as AdminTypeController;
use App\Models\Brands;

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

Route::get('/', function () {
    return view('welcome');
})->name('front.index');

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
    Route::resource('/type', AdminTypeController::class);
    Route::post('/type/{type}/restore', [AdminTypeController::class, 'restore'])->name('type.restore');
    Route::delete('/type/{type}/deletePermanent', [AdminTypeController::class, 'deletePermanent'])->name('type.deletePermanent');
});
