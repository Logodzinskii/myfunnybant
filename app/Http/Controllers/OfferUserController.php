<?php

namespace App\Http\Controllers;

use App\Models\OfferUser;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferUserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * контроллер работы с заказами пользователей
     */
    public function index()
    {

        if(Auth::user()->role === 1){
            $cart = OfferUser::all();
        }else{
            $cart = OfferUser::where('user_id','=',Auth::user()->id)->get();
            $totalSum = UserCart::where('user_id','=',Auth::user()->id)->get();
        }

        return view('main.allOffers', ['carts'=>$cart,
            'totalQuantity'=>'',
            'totalSum'=>$totalSum->sum('total_price')]);
    }

}
