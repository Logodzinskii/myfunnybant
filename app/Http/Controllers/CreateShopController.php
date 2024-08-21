<?php

namespace App\Http\Controllers;
use App\Http\Controllers\yandex\YandexYmlGenerator;
use App\Models\Offers;
use App\Models\OzonShop;
use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;

class CreateShopController extends Controller
{
    public function createShop($last_id = null)
    {
        $offers=[];
        $offers = ['OzonShop'=>$this->create(),
                   'Price'=>$this->createPrice(),
                  ];
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
        foreach ($arrOzonItems['result'] as $off){
            if((OzonShopItem::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                OzonShopItem::where('ozon_id', '=', $off['id'])
                ->update(['ozon_id'=>$off['id'],
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
                OzonShop::where('ozon_id', '=', $off['id'])
                    ->update([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                    'like_count'=>0,
                ]);
                $offers[]=[
                    $off['id']=>'update'
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
                OzonShop::create([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                    'like_count'=>0,
                ]);
                $offers[]=[$off['id']=>'create'];
            }

        }
        $yml = New YandexYmlGenerator();
        $yml->createYmlFile();
        
        return ' Товары на сайте успешно обновлены';

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

            if((OzonShop::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                OzonShop::where('ozon_id', '=', $off['id'])
                    ->update([
                        'ozon_id'=>$off['id'],
                        'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                        'like_count'=>0,
                    ]);
                $arr[] = ['Update at OzonShop' => $off['id']];
            }else{
                OzonShop::create([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                    'like_count'=>0,
                ]);
                $arr[] = ['Create at OzonShop' => $off['id']];
            }
        }
        return $arr;
    }

    protected function createPrice()
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

            if((StatusPriceShopItems::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                $price = $this->GetPrice($off['id'])[0];
                StatusPriceShopItems::where('ozon_id', '=', $off['id'])->
                update([
                    'ozon_id'=>$off['id'],
                    'status'=>'update',
                    'price'=>$price['price']['old_price'],
                    'action_price'=>$price['price']['marketing_price']+$price['price']['marketing_price']*0.2,
                    'fbs'=>0,
                    'fbo'=>0,
                ]);
                $arr[] = ['Update at Price' => $off['id']];
            }else{
                $price = $this->GetPrice($off['id'])[0];
                StatusPriceShopItems::create([
                    'ozon_id'=>$off['id'],
                    'status'=>'create',
                    'price'=>$price['price']['old_price'],
                    'action_price'=>$price['price']['marketing_price']+$price['price']['marketing_price']*0.2,
                    'fbs'=>0,
                    'fbo'=>0,
                ]);

                $arr[] = ['Create at Price' => $off['id']];
            }
        }
        return $arr;
    }
    protected function GetPrice($product_id)
    {
        $data = '{
            "filter": {

            "product_id": [
                "'.$product_id.'"
            ],
            "visibility": "ALL"
            },
            "last_id": "",
            "limit": 100
        }';
        $method = '/v4/product/info/prices';

        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        return $arrOzonItems['result']['items'];
    }

    public function maxLike()
    {
        $like = OzonShop::where('like_count', '>', 0)
            ->orderBy('like_count', 'desc')
            ->get();
        $offers=[];
        foreach ($like as $item)
        {
            $data = '{
                "filter": {
                    "product_id": [
                        "'.$item->ozon_id.'"
                    ],
                    "visibility": "ALL"
                },
                "limit": 100,
                "last_id": "okVsfA==«",
                "sort_dir": "ASC"
            }';
            $method = '/v3/products/info/attributes';
            $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);
            $offers[] = new Offers([
                'name'=>$arrOzonItems['result'][0]['name'],
                'images'=>$arrOzonItems['result'][0]['images'],
                //'attributes'=>$off['attributes'][0]['attribute_id'],
                'attributes'=>[
                    'id'=>$arrOzonItems['result'][0]['id'],
                    'category'=> $arrOzonItems['result'][0]['category_id'],
                    'type'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 8229),
                    'header'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 4180),
                    'description'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 4191),
                    'colors'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 10096),
                    'like'=>$item['like_count'],
                ],
                'price'=>$item['like_count']
            ]);
        }

        return view('admin.adminAllLike', ['data'=>[$offers]]);
    }
}
