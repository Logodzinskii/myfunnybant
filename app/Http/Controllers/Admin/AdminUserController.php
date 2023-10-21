<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferUser;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {

        $cart = OfferUser::all();
        $totalSum = UserCart::all();


        return view('main.allOffers', ['carts'=>$cart,
            'totalQuantity'=>'',
            'totalSum'=>$totalSum->sum('total_price')]);
    }
}
