<?php

use App\Http\Controllers\ActionOzonController;
use App\Http\Controllers\Admin\SaleItemsController;
use App\Http\Controllers\Admin\shop\productsShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CreateShopController;
use App\Http\Controllers\FinanceOzonController;
use App\Http\Controllers\OzonShopController;
use App\Http\Controllers\UserCartController;
use App\Http\Controllers\yandex\YandexYmlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ozonController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\Pages\PageContentController;
use App\Http\Controllers\VkParserController;

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
Route::get('/filter/{funnel}',[ozonController::class, 'index']);
Route::get('/filter/colors/{funnel}',[ozonController::class, 'color']);
Route::get('/filter/material/{funnel}',[ozonController::class, 'material']);
Route::get('/filter/price/{funnel}',[ozonController::class, 'price']);
Route::get('/shop/{offer_chpu}', [ozonController::class, 'showItem'])->name('shop');

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

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth','isAdmin'])->group(function() {
    /**
     * Маршруты для управления продажами с ярмарок администратором
     */
    Route::controller(SaleItemsController::class)->group(function(){
        Route::get('/admin/show/all/items/',  'showAllSaleItems');
        Route::get('/admin/total/year/','index');
        Route::get('/admin/sale/date/','showDateBetween');
        Route::post('/admin/sale/sum/datebetween','sumDateBetween')
            ->name('sum.date.between');
        Route::get('/admin/finance/ozon', 'ozonFinance')
            ->name('admin.finance.ozon');
        Route::post('/admin/sale/edit/date', 'editDateSale')
            ->name('edit.date.sale');
    })->middleware('auth');

    Route::controller(FinanceOzonController::class)->group(function(){
        Route::get('/admin/finance/read/csv', 'readCsv');
        Route::get('/admin/finance/show', 'showFinanceReport');
        Route::get('/admin/finance/load/ozon/csv',function (){
            return view('admin.charts.csvloader');
        });
        Route::post('/admin/finance/load/ozon/csv/file', 'loadCsv')
            ->name('file.upload.post');
    });
    /**
     * Маршруты для управления онлайн магазином администратором
     */
    Route::controller(CreateShopController::class)->group(function(){
        Route::get('/admin/maxlike', 'maxLike');
        Route::get('/admin/createShop/', 'createShop');
    })->middleware('auth');;

    /**
     * Работа с заказами пользователей
     */
    Route::get('/admin/view/offers/',[AdminUserController::class,'index']);
    Route::post('/admin/view/offers/',[AdminUserController::class,'index']);
    Route::put('/admin/update/status/offers/',[AdminUserController::class, 'update']);
    Route::post('/admin/track/add', [AdminUserController::class, 'addTrack']);

    /**
     * Работа с товарами магазина
     */
    Route::get('/admin/show/all/products', [productsShopController::class,'index']);

    /**
     * создание блогов на сайте
     */
    Route::controller(BlogsController::class)->group(function(){
        Route::get('/admin/blog/maker', 'index');
        Route::get('/admin/blog/maker/list', 'list')->name('list.admin.blog');
        Route::get('/admin/blog/maker/delete/{id}', 'delete')->name('delete.blog.post');
        Route::post('/admin/create/blog','create')->name('create.blog.post');
        Route::post('/admin/blog/save/image','saveImage')->name('blog.save.image');
    });
    
});

/**
 * Маршруты для работы с корзиной пользователем
 */
Route::controller(CartController::class)->group(function(){
    Route::post('user/add/cart','pushToCart')
        ->name('add.cart');
    Route::post('/user/update/quantity', 'updateCart');
    Route::post('/user/delete/cart', 'deleteCart');
    Route::post('/user/cart/total','getCountCartItem')->name('get.total');
    Route::get('/user/view/cart', 'indexCart');
    Route::post('/user/create/offer', 'createOffer')
        ->name('user.create.offer');
    Route::get('/user/get/cart','index');
    Route::get('/user/confirm', 'codeConfirm');
    Route::post('/user/confirm/code', 'codeConfirm');
});

Route::get('/security/',[CartController::class,'confirmLink']);
/**
 * Маршруты для работы с заказами пользователем
 */
Route::controller(UserCartController::class)->group(function(){

    Route::get('/user/send/mailtest', 'sendMailTest');

    Route::post('/user/delete/offer','deleteOffer');
    Route::get('/home','index');
})->middleware('auth');

/**
 * Маршруты счетчиков
 */
Route::get('/counter/',[CartController::class, 'counter'])->name('counter');

/** yml feed
 *
 */

Route::get('/yml/', [YandexYmlGenerator::class, 'createYmlFile']);
Route::get('/session/out', function (){
    session()->flush();
    return print_r(session()->all());
});
Route::get('/session/token', function (){
    \Cart::session('_token');
    $items = \Cart::getContent();
    return $items;
});

Route::get('/privacy', function (){
    return view('privacy');
});

/**
 * blog page
 */


 Route::get('/blogs', [PageContentController::class, 'index']);
 Route::get('/blog/{chpu}', [PageContentController::class, 'blog']);


 /**
  * Route::get('/vk/post/{count}/{startPosition}',[VkParserController::class, 'getVkPosts']);
*/
