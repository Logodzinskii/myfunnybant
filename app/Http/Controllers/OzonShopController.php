<?php

namespace App\Http\Controllers;

use App\Models\Offers;
use App\Models\OzonShop;
use Illuminate\Http\Request;
use App\Http\Controllers\ozonController;
use App\Http\Controllers\StatGetOzon;

class OzonShopController extends Controller
{

    public function create()
    {
        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';

        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        /**
         * 8229 - Бант для волос
         * 4180 - заголовок
         * 4191 - описание
         * 9725 - сезон
         * 22336 - ключевые слова
         * 9024 - ид товара b-01-01
         * 10096 - цвета
         * 5309 - материал
         * 13214 - от скольки возрстная категория
         * 13215 - до скольки лет
         */

        $arr = [];
        foreach ($arrOzonItems['result'] as $off){

            $res = OzonShop::where('ozon_id', $off['id'])->get();

            if((count($res) == 0))
            {
                $arr[] = OzonShop::create([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>'',
                    'like_count'=>0,
                ]);
            }else{
                $arr[] = 'такой id уже есть: '. $off['id'];
            }
        }
        return $arr;
    }

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

            }

        return $res[0]->like_count;
    }

    public function viewLike()
    {
        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';
        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        $offers=[];
        /**
         * 8229 - Бант для волос
         * 4180 - заголовок
         * 4191 - описание
         * 9725 - сезон
         * 22336 - ключевые слова
         * 9024 - ид товара b-01-01
         * 10096 - цвета
         * 5309 - материал
         * 13214 - от скольки возрстная категория
         * 13215 - до скольки лет
         */
        //return $arrOzonItems['result'];
        foreach ($arrOzonItems['result'] as $off){
            if(session()->has('ozon_id') && array_search($off['id'], session()->get('ozon_id')) !== false)
            {
                $like = OzonShop::where('ozon_id', $off['id'])->get();
                $like =$like[0]->like_count;
                $offers[] = new Offers([
                    'name'=>$off['name'],
                    'images'=>$off['images'],
                    //'attributes'=>$off['attributes'][0]['attribute_id'],
                    'attributes'=>[
                        'id'=>$off['id'],
                        'category'=> $off['category_id'],
                        'type'=>StatGetOzon::attributeFilter($off['attributes'], 8229),
                        'header'=>StatGetOzon::attributeFilter($off['attributes'], 4180),
                        'description'=>StatGetOzon::attributeFilter($off['attributes'], 4191),
                        'colors'=>StatGetOzon::attributeFilter($off['attributes'], 10096),
                        'like'=>$like,
                    ],
                    'price'=>''
                ]);
            }

        }
        return view('main.index', ['data'=>[$offers]]);
    }
}
