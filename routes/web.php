<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ozonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ozonController::class, 'showCategoryAttributeValues']);

Route::get('/category/{offer_id}', [ozonController::class, 'showItem']);

Route::post('/information/', [ozonController::class, 'showItemPost'])->name('shop.information');

/**
 * Маршруты для панели администрирования
 */

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth','isAdmin'])->group(function() {
    Route::get('/admin/show/all/items/', [\App\Http\Controllers\Admin\SaleItemsController::class, 'showAllSaleItems'])->middleware('auth');
});
