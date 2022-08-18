<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Функция получает на входе offer_id например br-1-3
     * @param $offer_id
     * Полученный результат из API seller ozon записывает в array
     */
    public function showItem($offer_id)
    {

        $data = '{
            "offer_id": "'.$offer_id.'" ,
            "sku":0
        }';
        $method = '/v2/product/info';

        $arrOzonItems = self::curl_request_ozon($data, $method);
        /**
         * получаю ответ
         * {
        "id": 266673018,
        "name": "Бант для волос \"Зефирка\" 2 шт., на резинке, праздничные, нарядные. Myfunnybant",
        "offer_id": "b-02-02",
        "barcode": "OZN554024534",
        "buybox_price": "",
        "category_id": 55592804,
        "created_at": "2022-04-12T16:15:02.330161Z",
        "images": [
        "https://cdn1.ozone.ru/s3/multimedia-1/6375670765.jpg",
        "https://cdn1.ozone.ru/s3/multimedia-v/6375670759.jpg",
        "https://cdn1.ozone.ru/s3/multimedia-w/6375670760.jpg",
        "https://cdn1.ozone.ru/s3/multimedia-q/6375670754.jpg",
        "https://cdn1.ozone.ru/s3/multimedia-p/6375670753.jpg",
        "https://cdn1.ozone.ru/s3/multimedia-u/6375670758.jpg"
        ],
        "marketing_price": "355.0000",
        "min_ozon_price": "",
        "old_price": "639.0000",
        "premium_price": "",
        "price": "429.0000",
        "recommended_price": "",
        "min_price": "339.0000",
        "sources": [
        {
        "is_enabled": true,
        "sku": 554024534,
        "source": "fbo"
        },
        {
        "is_enabled": true,
        "sku": 554024535,
        "source": "fbs"
        }
        ],
        "stocks": {
        "coming": 0,
        "present": 55,
        "reserved": 0
        },
        "errors": [],
        "vat": "0.0",
        "visible": true,
        "visibility_details": {
        "has_price": true,
        "has_stock": true,
        "active_product": false
        },
        "price_index": "0.00",
        "commissions": [
        {
        "percent": 8,
        "min_value": 0,
        "value": 28.4,
        "sale_schema": "fbo",
        "delivery_amount": 0,
        "return_amount": 0
        },
        {
        "percent": 8,
        "min_value": 0,
        "value": 28.4,
        "sale_schema": "fbs",
        "delivery_amount": 0,
        "return_amount": 0
        },
        {
        "percent": 8,
        "min_value": 0,
        "value": 28.4,
        "sale_schema": "rfbs",
        "delivery_amount": 0,
        "return_amount": 0
        }
        ],
        "volume_weight": 0.1,
        "is_prepayment": false,
        "is_prepayment_allowed": false,
        "images360": [],
        "color_image": "",
        "primary_image": "https://cdn1.ozone.ru/s3/multimedia-s/6375670756.jpg",
        "status": {
        "state": "price_sent",
        "state_failed": "",
        "moderate_status": "approved",
        "decline_reasons": [],
        "validation_state": "success",
        "state_name": "Продается",
        "state_description": "",
        "is_failed": false,
        "is_created": true,
        "state_tooltip": "",
        "item_errors": [],
        "state_updated_at": "2022-08-10T04:49:36.049561Z"
        },
        "state": "",
        "service_type": "IS_CODE_SERVICE",
        "fbo_sku": 554024534,
        "fbs_sku": 554024535,
        "currency_code": "RUB",
        "is_kgt": false
        }
         * заполняю массив
         */
        $this->responseOzonArray[] = $arrOzonItems['result'];
        /*if($arrOzonItems['result']['category_id'] == 78286803){

        }*/


    }

    /**
     * @param Request|null $lastId
     * @return Response|string
     */
    public function showAllItems($last_id = null)
    {

        if($last_id == null)
        {
            $last_id = '';
        }
        /**
        * бантики category_id = 55592804
        * чокеры category_id = 78286803
        * наборы, комплекты бантиков category_id = 78059066
         * Бант \"Пироженка\" category_id = 78059088
         *
         */
        $data = '{
        "filter": {

            "visibility": "IN_SALE"
            },
            "limit":"1",
            "last_id": "'.$last_id.'"
        }';
        $method = '/v2/product/list';

        /**
         * $arrOzonItems список товаров json {
         * "items":
         * [
         *      {"product_id":261984365,"offer_id":"br-1-3"},
         *      {"product_id":266673018,"offer_id":"b-02-02"}
         * ],
         * "total":102,
         * "last_id":"WzI2NjY3MzAxOCwyNjY2NzMwMThd"
         * }
         */

        $arrOzonItems = self::curl_request_ozon($data, $method);
        file_put_contents('log.txt',json_encode($this->getResponseOzonArray()));


        if (isset($arrOzonItems['result'])){

            foreach ($arrOzonItems['result']['items'] as $item)
            {
                self::showItem($item['offer_id']);
            }


            $this->pagesArray = $arrOzonItems['result']['last_id'];


            $resArr = array_merge($this->getResponseOzonArray(), ['last_id' => $arrOzonItems['result']['last_id']]);
            file_put_contents('log1.txt',json_encode($resArr));
            $data = [
                'items'=>$this->getResponseOzonArray(),
                'last_id' => $arrOzonItems['result']['last_id'],
            ];
            $view = view('shop')->with('data', $data);
            self::showCategoryAttributeValues();
            return new Response($view);

        }else{
            return 'error';
        }
    }

    public function showCategoryAttributeValues($last_id = null)
    {
        if($last_id == null)
        {
            $last_id = '';
        }

        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 500,
        "last_id": "",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';
        $arrOzonItems = self::curl_request_ozon($data, $method);

        $arrBant = [];
        $arrChocker = [];
        $arrCollection = [];
        $arrBantCake = [];

        $last_id = '';
        /**
         * бантики category_id = 55592804
         * чокеры category_id = 78286803
         * наборы, комплекты бантиков category_id = 78059066
         * Бант \"Пироженка\" category_id = 78059088
         *
         */
        foreach ($arrOzonItems['result'] as $item)
        {
            if($item['category_id'] == 55592804)
            {
                $arrBant[]=$item;
                $last_id = $item['last_id'];
            }
            if($item['category_id'] == 78286803)
            {
                $arrChocker[]=$item;
                $last_id = $item['last_id'];
            }
            if($item['category_id'] == 78059066)
            {
                $arrCollection[]=$item;
                $last_id = $item['last_id'];
            }
            if($item['category_id'] == 78059088)
            {
                $arrBantCake[]=$item;
                $last_id = $item['last_id'];
            }
        }

        $data = [
            'bant'=>$arrBant,
            'chocker'=> $arrChocker,
            'collection'=> $arrCollection,
            'cake'=> $arrBantCake,
            'last_id' => $last_id,
        ];
        //file_put_contents('attrVal.txt', json_encode($arr));
        $view = view('shop')->with('data', $data);
        return new Response($view);


    }
    /**
     * @return array
     */
    public function getResponseOzonArray(): array
    {
        return $this->responseOzonArray;
    }

}
