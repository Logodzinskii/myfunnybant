<?php

namespace App\Http\Controllers;

use App\Models\OfferUser;
use App\Models\UserCart;
use Illuminate\Http\Request;

class OfferUserController extends Controller
{
    public function index()
    {
        $cart = OfferUser::all();
        return view('admin.shop.allOffers', ['carts'=>$cart]);
    }
}
