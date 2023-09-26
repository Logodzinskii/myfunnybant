<?php

namespace App\Http\Controllers;

use App\Models\Offers;
use App\Models\OzonShopItem;
use Illuminate\Support\Facades\Cache;

class ActionOzonController extends Controller
{

    public function getItemsInActions()
    {
        $resultAllItemsInActions = [];
        $allAction = StatGetOzon::getAllAction();

        if(!is_array($allAction))
        {
           die($allAction . 'none action');
        }
        foreach ($allAction as $action)
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

               $product[] = OzonShopItem::where('ozon_id', '=', $off['id'])->get();
            }
            if(count($product)!= 0){
                $res[]= [
                        'actionTitle' => $resAction['action']['name'],
                        'product' =>  $product
                ];
            }
        }
        //return $res;
        return view('main.actions', ['data'=>$res]);
    }
}
