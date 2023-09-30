<?php

namespace App\Http\Controllers;

use App\Models\OzonShopItem;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function pushToCart(Request $request)
    {
        $userId = Auth::user();
        $product = OzonShopItem::find($request->ozon_id);
        \Cart::session($userId)->add([
            'id' => '$rowId',
            'name' => '$Product->name',
            'price' => '$Product->price',
            'quantity' => 4,
            'attributes' => array(),
            'associatedModel' => '$Product'
        ]);
    }
}
