<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ozonController
{
    public $responseOzonArray = [];


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
        ]);
    }

}
