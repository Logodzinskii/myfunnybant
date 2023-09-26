<?php

namespace App\Http\Controllers;

use App\Models\OzonShop;
use App\Models\OzonShopItem;
use Illuminate\Http\Request;

class CreateShopController extends Controller
{
    public function createShop($last_id = null)
    {
        self::create();
        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "'.$last_id.'",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';
        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        $offers=[];

        foreach ($arrOzonItems['result'] as $off){
            if((OzonShopItem::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                $offers[]=[
                    $off['id']=>'уже есть в базе данных'
                ];

            }else{

                OzonShopItem::create([
                    'ozon_id'=>$off['id'],
                    'name'=>$off['name'],
                    'images'=>json_encode($off['images']),
                    'category'=>$off['category_id'],
                    'type'=>StatGetOzon::attributeFilter($off['attributes'], 8229)[0],
                    'header'=>StatGetOzon::attributeFilter($off['attributes'], 4180)[0],
                    'description'=>StatGetOzon::attributeFilter($off['attributes'], 4191)[0],
                    'colors'=>json_encode(StatGetOzon::attributeFilter($off['attributes'], 10096)),
                    'width'=>$off['width'],
                    'height'=>$off['height'],
                    'depth'=>$off['depth'],
                    'material'=>json_encode(StatGetOzon::attributeFilter($off['attributes'], 5309)),
                ]);
                $offers[]=[$off['id']=>'создан'];
            }

        }
        return $offers;

    }

    protected function create()
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

        $arr = [];
        foreach ($arrOzonItems['result'] as $off){

            $res = OzonShop::where('ozon_id', $off['id'])->get();

            if((count($res) == 0))
            {
                $arr[] = OzonShop::create([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                    'like_count'=>0,
                ]);
            }else{
                $arr[] = 'такой id уже есть: '. $off['id'];
            }
        }
        return $arr;
    }
}
