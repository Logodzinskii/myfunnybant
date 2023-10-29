<?php

namespace App\Http\Controllers;

use App\Models\OzonShop;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ozonController extends Controller
{

    public function index( $funnel = null)
    {
        if(($funnel != null))
        {
            $data  = [DB::table('ozon_shop_items')
                ->where('category', '=', $funnel)
                ->orderBy('ozon_id','desc')
                ->get()
            ];
        }else{
            $data = [DB::table('ozon_shop_items')
                ->orderBy('ozon_id','desc')
                ->get()
            ];
            $funnel = '';
        }

        return view('main.index', [
            'data'=> $data,
            'link'=> $funnel,
        ]);
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
