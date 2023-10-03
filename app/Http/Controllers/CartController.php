<?php

namespace App\Http\Controllers;

use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function pushToCart(Request $request)
    {
        if(Auth::check())
        {
            $userId = Auth::user();
            $product = OzonShopItem::where('ozon_id', '=', $request->ozon_id)->firstOrFail();

            \Cart::session($userId)->add([
                'id'=>$product->id,
                'user_id' => $userId,
                'name' => $product->name,
                'price' => StatusPriceShopItems::where('ozon_id', '=', $request->ozon_id)->firstOrFail()->price,
                'quantity' => 1,
                'attributes' => [],
                'associatedModel' => $product,
            ]);
            return $this->getCountCartItem();
        }else{
            return 0;
        }
    }

    public function indexCart()
    {
        $userId = Auth::user();
        \Cart::session($userId);
        $items = \Cart::getContent();

        return view('main.cart', ['cart'=>$items]);
    }

    public function getCountCartItem()
    {
        if(Auth::check()){
            $userId = Auth::user();

            $total = \Cart::session($userId)->getSubTotal();
            $totalQuantity = \Cart::session($userId)->getTotalQuantity();
            return [$total,$totalQuantity];
        }else{
            return [0,0];
        }

    }


    public function updateCart(Request $request)
    {

        $userId = Auth::user();
        \Cart::session($userId)->update($request->id,
            [
                'quantity' => $request->quantity,
            ]);
        $items = \Cart::getContent();
        return $items[$request->id]->quantity;
    }

    public function deleteCart(Request $request)
    {
        $userId = Auth::user();
        \Cart::session($userId)->remove($request->id);

        return $request->id;
    }

}
