<?php

use App\Http\Controllers\OzonShopController;
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

Route::get('/shop/{offer_chpu}', [ozonController::class, 'showItem'])->name('shop');

Route::post('/information/', [ozonController::class, 'showItemPost'])->name('shop.information');

Route::get('/sell/{url}', function($url){

    $fullUrl = \Illuminate\Support\Facades\Request::userAgent();
    $ipVisitor = \Illuminate\Support\Facades\Request::ip();
    $path = \Illuminate\Support\Facades\Request::path();
    $fullUrl = \Illuminate\Support\Facades\Request::fullUrl();
    $header = \Illuminate\Support\Facades\Request::header('X-Header-Name');
    $userAgent = \Illuminate\Support\Facades\Request::server('HTTP_USER_AGENT');
        preg_match('/Bot/', $fullUrl, $output_array);
        if(preg_match('/Bot/', $fullUrl, $output_array)==0){
            \App\Events\ClickOzonLink::dispatch(' ip: ' . $ipVisitor . '; Браузер ' . $userAgent . '; Url ' . $fullUrl);
        }

    return redirect('https://www.ozon.ru/seller/myfunnybant-302542/aksessuary-7697/?miniapp=seller_302542&text='.$url);

})->name('seller.ozon');

Route::post('/seller/', [ozonController::class, 'showItem'])->name('seller.show');

Route::get('my/like', [OzonShopController::class,'viewLike']);

Route::post('addlike',[OzonShopController::class, 'addLike']);
/**
 * Маршруты для панели администрирования
 */
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth','isAdmin'])->group(function() {
    Route::get('/admin/show/all/items/', [\App\Http\Controllers\Admin\SaleItemsController::class, 'showAllSaleItems'])->middleware('auth');
    Route::get('/admin/total/year/',[\App\Http\Controllers\Admin\SaleItemsController::class,'index'])->middleware('auth');
    Route::get('/admin/sale/date/',[\App\Http\Controllers\Admin\SaleItemsController::class,'showDateBetween'])->middleware('auth');
    Route::post('/admin/sale/sum/datebetween',[\App\Http\Controllers\Admin\SaleItemsController::class,'sumDateBetween'])->middleware('auth')->name('sum.date.between');
    Route::get('/ozon/',[OzonShopController::class, 'create']);
});

