<?php

namespace App\Http\Controllers;

use App\Models\OfferUser;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferUserController extends Controller
{
    public function index()
    {

        if(Auth::user()->role === 1){
            $cart = OfferUser::all();
        }else{

            $cart = OfferUser::where('user_id','=',Auth::user()->id)->get();
        }

        return view('main.allOffers', ['carts'=>$cart]);
    }

}
