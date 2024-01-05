<?php

namespace App\Http\Controllers;

use App\Events\ClickOzonLink;
use App\Events\UserSearch;
use App\Http\Controllers\StatGetOzon;
use App\Listeners\UserSearchListener;
use App\Models\Offers;
use App\Models\OzonShop;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;
use App\Http\Controllers\ozonController;

class OzonShopController extends Controller
{

    public function addLike(Request $request)
    {
        $id = $request->id;
        /**
         * проверим сессию likeSession если пользователь впервые то занесем в бд, если нет, то ничего не вернем
         */
        $res = [];

            $res = OzonShop::where('ozon_id', $id)->get();

            if ($request->session()->has('ozon_id') && array_search($id, $request->session()->get('ozon_id')) !== false)
            {

                $res = OzonShop::where('ozon_id', $id)->get();

            } else {

                if ($request->session()->exists('ozon_id'))
                {

                    $request->session()->push('ozon_id', $id);

                }else{
                    session(['like' => '1',
                        'ozon_id'=>[$id]]);

                }

                $curentCount = OzonShop::where('ozon_id', $id)->get();

                OzonShop::where('ozon_id', $id)
                        ->update(['like_count' => $curentCount[0]->like_count + 1]);
                $res = OzonShop::where('ozon_id', $id)->get();
                ClickOzonLink::dispatch('https://myfunnybant.ru/shop/' . $res[0]->url_chpu);
            }

        return $res[0]->like_count;
    }

    public function viewLike()
    {

        $offers = [];

            if(session()->has('ozon_id'))
            {
                foreach (session()->get('ozon_id') as $off){
                    $offers[] = OzonShopItem::where('ozon_id', $off)
                        ->paginate(20);
                }
            }

        return view('main.index', ['data'=>$offers]);
    }

    public function find(Request $request)
    {
        $request->validate([
            'funnel' => 'required|max:150',
        ]);

        ClickOzonLink::dispatch($request->get('funnel'));

        return view('main.index', [
            'data'=>
                [
                    OzonShopItem::search($request->get('funnel'))
                        ->paginate(20)
                ]
        ]);

    }
}
