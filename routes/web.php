<?php

use App\Http\Controllers\ActionOzonController;
use App\Http\Controllers\Admin\SaleItemsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConcurentParserController;
use App\Http\Controllers\CreateShopController;
use App\Http\Controllers\OfferUserController;
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

Route::get('/', [ozonController::class, 'index']);

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

//Route::post('/seller/', [ozonController::class, 'showItem'])->name('seller.show');

Route::get('my/like', [OzonShopController::class,'viewLike']);

Route::post('addlike',[OzonShopController::class, 'addLike']);
Route::post('find', [OzonShopController::class, 'find'])->name('find');

Route::get('/actions/', [ActionOzonController::class, 'getItemsInActions']);
/**
 * Маршруты для панели администрирования
 */
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth','isAdmin'])->group(function() {
    Route::get('/admin/show/all/items/', [SaleItemsController::class, 'showAllSaleItems'])->middleware('auth');
    Route::get('/admin/total/year/',[SaleItemsController::class,'index'])->middleware('auth');
    Route::get('/admin/sale/date/',[SaleItemsController::class,'showDateBetween'])->middleware('auth');
    Route::post('/admin/sale/sum/datebetween',[SaleItemsController::class,'sumDateBetween'])->middleware('auth')->name('sum.date.between');
    Route::get('/admin/maxlike',[SaleItemsController::class, 'maxLike']);
    Route::get('/admin/createShop/', [CreateShopController::class, 'createShop']);

    /**
     * Работа с заказами
     */
    Route::get('/admin/view/offers',[OfferUserController::class,'index']);
});

/**
 * Парсер
 *
 */

Route::get('parse',[ConcurentParserController::class, 'getUrl']);

/**
 * Cart
 */

Route::post('user/add/cart', [CartController::class, 'pushToCart'])
    ->name('add.cart');
Route::post('/user/update/quantity', [CartController::class, 'updateCart'])
    ->middleware('auth');
Route::post('/user/delete/cart', [CartController::class, 'deleteCart'])
    ->middleware('auth');
Route::post('/user/cart/total',[CartController::class, 'getCountCartItem']);
Route::get('user/view/cart', [CartController::class, 'indexCart'])
    ->middleware('auth');
Route::post('/user/create/offer', [\App\Http\Controllers\UserCartController::class, 'createOffer'])
    ->name('user.create.offer')
    ->middleware('auth');
Route::get('/user/send/mailtest', [\App\Http\Controllers\UserCartController::class,'sendMailTest']);
/**
 * Просмотр товаров в корзине
 */
Route::get('/user/get/cart',[OfferUserController::class,'index'])->middleware('auth');
