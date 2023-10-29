<?php

namespace App\Http\Controllers\Admin\shop;

use App\Http\Controllers\Controller;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;

class productsShopController extends Controller
{
    public function index()
    {
        return view('admin.shop.shopItems', ['products'=>OzonShopItem::where('id','>','0')->get()]);
    }
}
