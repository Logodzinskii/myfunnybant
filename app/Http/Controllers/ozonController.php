<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ozonController
{
    public $responseOzonArray = [];

    protected function curl_request_ozon($data, $method)
    {
        $clientId = config('ozon.CLIENT_ID'); //айди шопа
        $apiKey = config('ozon.OZONTOKEN');; // ключ апи
        $url = 'http://api-seller.ozon.ru'.$method;
        $headers = array(
            'Content-Type: application/json',
            'Host: api-seller.ozon.ru',
            'Client-Id: '.$clientId,
            'Api-Key: '.$apiKey
        ) ;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $html = curl_exec($ch);

        curl_close($ch);

        return (json_decode($html, true));
    }
    public function showItem($offer_id)
    {

        $data = '{
            "offer_id": "'.$offer_id.'" ,
            "sku":0
        }';
        $method = '/v2/product/info';

        $arrOzonItems = self::curl_request_ozon($data, $method);

        $this->responseOzonArray[] = $arrOzonItems['result'];

    }

    public function showAllItems()
    {
        $data = '{
            "limit":7
        }';
        $method = '/v2/product/list';

        /**
         * $arrOzonItems список товаров json {"items":[{"product_id":261984365,"offer_id":"br-1-3"},{"product_id":266673018,"offer_id":"b-02-02"}],"total":102,"last_id":"WzI2NjY3MzAxOCwyNjY2NzMwMThd"}
         */

        $arrOzonItems = self::curl_request_ozon($data, $method);


        if (isset($arrOzonItems['result'])){

            foreach ($arrOzonItems['result']['items'] as $item)
            {
                self::showItem($item['offer_id']);
            }

            file_put_contents('log.txt', json_encode($this->getResponseOzonArray()));
            $view = view('shop')->with(['items'=>$this->getResponseOzonArray()]);

            return new Response($view);

        }else{
            return 'error';
        }
    }

    /**
     * @return array
     */
    public function getResponseOzonArray(): array
    {
        return $this->responseOzonArray;
    }
}
