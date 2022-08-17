<?php

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

Route::get('/', function () {
    return view('index', ['webTitle' => 'Myfunnybant аксессуары для волос ручной работы', 'meta'=>'ручная работа']);
});
Route::get('/shop/', [ozonController::class, 'showCategoryAttributeValues']);

Route::get('/shop/{last_id}/', [ozonController::class, 'showCategoryAttributeValues']);
