<?php

namespace App\Http\Controllers;

use App\Models\Offers;
use App\Models\OzonShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActionOzonController extends Controller
{
    public function getAllAction()
    {

        $method = '/v1/actions';
        $clientId = config('ozon.CLIENT_ID'); //айди шопа
        $apiKey = config('ozon.OZONTOKEN');; // ключ апи
        $url = 'https://api-seller.ozon.ru'.$method;
        $headers = array(
            'Content-Type: application/json',
            'Host: api-seller.ozon.ru',
            'Client-Id: '.$clientId,
            'Api-Key: '.$apiKey
        ) ;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $html = curl_exec($ch);

        curl_close($ch);

        /**
         * Результат запроса занесем в cache на 20 минут
         */
        Cache::put('allItems', json_decode($html, true), now()->addMinutes(50));
        $html = json_decode($html, true);
        return $html['result'];
    }

    public function getItemsInActions()
    {
        $resultAllItemsInActions = [];
        foreach (self::getAllAction() as $action)
        {
            $data = '{
                "action_id": "'.$action['id'].'",
                "limit": 1000,
                "offset": 0
            }';
            $method = '/v1/actions/products';
            $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);
            $resultAllItemsInActions[]=[
                'action'=>[
                    'name'=>$action['title'],
                    'items'=>$arrOzonItems['result']['products']
                ]
            ];
        }
       //$arrOzonItems['result']['products'][0]['price'];
        $res=[];
        foreach ($resultAllItemsInActions as $resAction){
            $product=[];
            foreach ($resAction['action']['items'] as $off){
                $dataAttribute = '{
                "filter": {
                    "product_id": [
                        "'.$off['id'].'"
                    ],
                    "visibility": "ALL"
                },
                "limit": 100,
                "last_id": "okVsfA==«",
                "sort_dir": "ASC"
                }';
                $methodAttribute = '/v3/products/info/attributes';
                $ozonResult = StatGetOzon::getOzonCurlHtml($dataAttribute, $methodAttribute);
                if(isset($ozonResult['result']))
                {
                    $product[] = new Offers([
                        'id'=>$off['id'],
                        'name'=>$ozonResult['result'][0]['name'],
                        'images'=>$ozonResult['result'][0]['images'],
                        //'attributes'=>$off['attributes'][0]['attribute_id'],
                        'attributes'=>[
                            'id'=>$ozonResult['result'][0]['id'],
                            'category'=> $ozonResult['result'][0]['category_id'],
                            'type'=>StatGetOzon::attributeFilter($ozonResult['result'][0]['attributes'], 8229),
                            'header'=>StatGetOzon::attributeFilter($ozonResult['result'][0]['attributes'], 4180),
                            'description'=>StatGetOzon::attributeFilter($ozonResult['result'][0]['attributes'], 4191),
                            'colors'=>StatGetOzon::attributeFilter($ozonResult['result'][0]['attributes'], 10096),
                            'like'=>'',
                        ],
                        'price'=>[$off['price'], $off['action_price']]
                    ]);

                }else{
                    return redirect('404');
                }
            }
            if(count($product)!= 0){
                $res[]= [
                        'actionTitle' => $resAction['action']['name'],
                        'product' =>  $product
                ];
            }
        }

        //return ['data'=>$res];
        return view('main.actions', ['data'=>$res]);
    }
}
