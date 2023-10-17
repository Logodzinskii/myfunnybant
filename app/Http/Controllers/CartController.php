<?php

namespace App\Http\Controllers;

use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use App\Models\UserCart;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public $user;

    public function __construct()
    {

    }

    protected function getUser()
    {
        if(Auth::check()) {
            $sessId = Auth::user();
        }else{
            if(session()->has('user')){
                $sessId = session()->get('user');
            }else{
                $sessId = session(['user'=>session()->getId()]);
            }
        }
        return $sessId;
    }

    public function pushToCart(Request $request)
    {
            $userId = $this->getUser();
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
    }

    public function indexCart()
    {

        \Cart::session($this->getUser());
        $items = \Cart::getContent();

        return view('main.cart', ['cart'=>$items]);
    }

    public function getCountCartItem()
    {
        $userId = $this->getUser();
        $total = \Cart::session($userId)->getSubTotal();
        $totalQuantity = \Cart::session($userId)->getTotalQuantity();

        return [$total,$totalQuantity];
    }


    public function updateCart(Request $request)
    {

        $userId = $this->getUser();
        \Cart::session($userId)->update($request->id,
            [
                'quantity' => $request->quantity,
            ]);
        $items = \Cart::getContent();
        return $items[$request->id]->quantity;
    }

    public function deleteCart(Request $request)
    {
        $userId = $this->getUser();
        \Cart::session($userId)->remove($request->id);

        return $request->id;
    }

    public function confirmLink(Request $request)
    {

        $hash = $request->link;
        $res = UserCart::where('status_offer', '=', $hash)->get();
        if(count($res)===0){
            return 'denied';
        }else{
            UserCart::where('status_offer', '=', $hash)
                ->update(['status_offer'=>'awaiting payment']);
            return redirect('/user/get/cart');
        }

    }

}
