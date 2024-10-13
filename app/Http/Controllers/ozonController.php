<?php

namespace App\Http\Controllers;

use App\Models\OzonShop;
use App\Models\OzonShopItem;
use App\View\Components\funnel;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ozonController
{
    public $responseOzonArray = [];

    public function index( $funnel = null)
    {

        $fullUrl = \Illuminate\Support\Facades\Request::userAgent();
        $ipVisitor = \Illuminate\Support\Facades\Request::ip();
        $path = \Illuminate\Support\Facades\Request::path();
        $fullUrl = \Illuminate\Support\Facades\Request::fullUrl();
        $header = \Illuminate\Support\Facades\Request::header('X-Header-Name');
        $userAgent = \Illuminate\Support\Facades\Request::server('HTTP_USER_AGENT');

        Log::debug('fulUrl' . $fullUrl . 'ipVisitor' . $ipVisitor . 'UserAgent' . $userAgent);
        
        if(($funnel != null))
        {
            $data  = [DB::table('ozon_shop_items')
                ->where('category', '=', $funnel)
                ->orderBy('ozon_id','desc')
                ->paginate(20)
            ];
        }else{
            $data = [DB::table('ozon_shop_items')
                ->orderBy('ozon_id','desc')
                ->paginate(20)
            ];
            $funnel = '';
        }

        return view('main.index', [
            'data'=> $data,
        ]);
    }

    public function color($funnel)
    {
        $colors = OzonShopItem::select('colors','ozon_id')
            ->get();
        $arr = [];

        foreach ($colors as $color)

        {
            foreach (json_decode($color->colors,true) as $color1)
            {
                if($color1 == $funnel){
                    $arr[]= $color->ozon_id;
                }
            }
        }

        return view('main.index', [
            'data'=>
                [
                    OzonShopItem::whereIn('ozon_id',$arr)
                        ->paginate(20)
                ]
        ]);
    }

    public function material($funnel)
    {
        $materials = OzonShopItem::select('material','ozon_id')
            ->get();
        $arr = [];

        foreach ($materials as $material)
        {
            foreach (json_decode($material->material,true) as $material1)
            {
                if($material1 == $funnel){
                    $arr[]= $material->ozon_id;
                }
            }
        }

        return view('main.index', [
            'data'=>
                [
                    OzonShopItem::whereIn('ozon_id',$arr)
                        ->paginate(20)
                ]
        ]);
    }

    public function price($funnel)
    {
        return view('main.index', [
            'data'=>
                [
                    DB::table('ozon_shop_items')
                        ->join('status_price_shop_items','ozon_shop_items.ozon_id', '=', 'status_price_shop_items.ozon_id')
                        ->select('ozon_shop_items.*', 'status_price_shop_items.action_price')
                        ->orderBy('status_price_shop_items.action_price',$funnel == 'max'?'desc':'asc')
                        ->paginate(20)
                ]
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
