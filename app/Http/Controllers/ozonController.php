<?php

namespace App\Http\Controllers;

use App\Models\OzonShop;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;

class ozonController extends Controller
{

    public function index()
    {
        return view('main.index', ['data'=>[OzonShopItem::all()]]);
    }

    public function showItem(Request $request, $offer_chpu = null)
    {
        if($offer_chpu == null){

            $offer_id = $request->id;

        }else{
            $offer_id = OzonShop::where('url_chpu', '=', $offer_chpu)->first();
            $offer_id = $offer_id->ozon_id;

        }

        return view('item', ['res'=>OzonShopItem::where('ozon_id', '=', $offer_id)->first()]);
    }


}
