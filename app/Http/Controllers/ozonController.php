<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\AppendStream;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ozonController extends Cache
{
    public $responseOzonArray = [];

    protected function curl_request_ozon($data, $method):array
    {
        /**
         * Проверим cache на предмет загруженного массива данных из озон
         *
         */
        $html = '';
        /*if (Cache::has('allItems'))
        {
            $html = Cache::get('allItems');

        }else{*/
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
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data,
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
        //}

        return $html;
    }

    /**
     * Функция получает на входе offer_id например br-1-3
     * @param $offer_id
     * Полученный результат из API seller ozon записывает в array
     */
    public function showItem($offer_id)
    {
        /**
         * Получу информацию о ценах
         */
        $data = '{

            "offer_id":  "",
            "product_id": "'.$offer_id.'",
            "sku": 0
        }';
        $method = '/v2/product/info';

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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $html = curl_exec($ch);

        curl_close($ch);
        /**
         * получаю ответ
         *
         {
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

        /**
         * Получу атрибуты товара
         */
        $data = '{
        "filter": {
        "product_id":[
            "'.$offer_id.'"
            ],
        "visibility": "ALL"
        },
        "limit": 1,
        "last_id": "",
        "sort_dir": "DESC"
        }';

        $method = '/v3/products/info/attributes';

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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $htmlAttr = curl_exec($ch);
        /**
         * {
        "result": [
        {
        "id": 213761435,
        "barcode": "",
        "category_id": 17038062,
        "name": "Пленка защитная для Xiaomi Redmi Note 10 Pro 5G",
        "offer_id": "21470",
        "height": 10,
        "depth": 210,
        "width": 140,
        "dimension_unit": "mm",
        "weight": 50,
        "weight_unit": "g",
        "images": [
        {
        "file_name": "https://cdn1.ozone.ru/s3/multimedia-f/6190456071.jpg",
        "default": true,
        "index": 0
        },
        {
        "file_name": "https://cdn1.ozone.ru/s3/multimedia-7/6190456099.jpg",
        "default": false,
        "index": 1
        },
        {
        "file_name": "https://cdn1.ozone.ru/s3/multimedia-9/6190456065.jpg",
        "default": false,
        "index": 2
        }
        ],
        "image_group_id": "",
        "images360": [ ],
        "pdf_list": [ ],
        "attributes": [
        {
        "attribute_id": 5219,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 970718176,
        "value": "универсальный"
        }
        ]
        },
        {
        "attribute_id": 11051,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 970736931,
        "value": "Прозрачный"
        }
        ]
        },
        {
        "attribute_id": 10100,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "false"
        }
        ]
        },
        {
        "attribute_id": 11794,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 970860783,
        "value": "safe"
        }
        ]
        },
        {
        "attribute_id": 9048,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "Пленка защитная для Xiaomi Redmi Note 10 Pro 5G"
        }
        ]
        },
        {
        "attribute_id": 5076,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 39638,
        "value": "Xiaomi"
        }
        ]
        },
        {
        "attribute_id": 9024,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "21470"
        }
        ]
        },
        {
        "attribute_id": 10015,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "false"
        }
        ]
        },
        {
        "attribute_id": 85,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 971034861,
        "value": "Brand"
        }
        ]
        },
        {
        "attribute_id": 9461,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 349824787,
        "value": "Защитная пленка для смартфона"
        }
        ]
        },
        {
        "attribute_id": 4180,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "Пленка защитная для Xiaomi Redmi Note 10 Pro 5G"
        }
        ]
        },
        {
        "attribute_id": 4191,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 0,
        "value": "Пленка предназначена для модели Xiaomi Redmi Note 10 Pro 5G. Защитная гидрогелевая пленка обеспечит защиту вашего смартфона от царапин, пыли, сколов и потертостей."
        }
        ]
        },
        {
        "attribute_id": 8229,
        "complex_id": 0,
        "values": [
        {
        "dictionary_value_id": 91521,
        "value": "Защитная пленка"
        }
        ]
        }
        ],
        "complex_attributes": [ ],
        "color_image": "",
        "last_id": ""
        }
        ],
        "total": 1,
        "last_id": "onVsfA=="
        }
         */
        curl_close($ch);
        file_put_contents('t.txt',json_encode($htmlAttr));
        return view('item', ['result' => json_decode($html,true), 'attributes'=>json_decode($htmlAttr,true)]);
        //$this->responseOzonArray[] = $arrOzonItems['result'];
        /*if($arrOzonItems['result']['category_id'] == 78286803){

        }*/


    }

    public function showItemPost(Request $request)
    {
        $product_id = $request->input('id');

        $data = '{

            "offer_id":  "",
            "product_id": "'.$product_id.'",
            "sku": 0
        }';
        $method = '/v2/product/info';

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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $html = curl_exec($ch);

        curl_close($ch);


        /**
         * получаю ответ
         *
        {
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
        return view('item', ['result' => json_decode($html,true)]);
        //$this->responseOzonArray[] = $arrOzonItems['result'];
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
        if($last_id != null)
        {
            $last_id = $last_id;
        }else
        {
            $last_id = '';
        }

        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "'.$last_id.'",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';
        $arrOzonItems = self::curl_request_ozon($data, $method);

        $arrBant = []; //55592804
        $arrChocker = [];//78286803
        $arrCollection = [];//78059066
        $arrBantCake = [];//78059088
        $arrAnother =[];
        $arr6 =[];
        $arr7 =[];
        $newResponse=[];
        $last_id = $arrOzonItems['last_id'];
        /**
         * бантики category_id = 55592804
         * чокеры category_id = 78286803
         * наборы, комплекты бантиков category_id = 78059066
         * Бант \"Пироженка\" category_id = 78059088         *
         */

        foreach ($arrOzonItems['result'] as $item)
        {
            //file_put_contents('te.txt', json_encode($item['category_id']) . '|', FILE_APPEND);
            if($item['category_id'] == 55592804)
            {
                $arrBant[]=$item;

            }
            if($item['category_id'] == 78286803)
            {
                $arrChocker[]=$item;

            }
            if($item['category_id'] == 78059066)
            {
                $arrCollection[]=$item;

            }
            if($item['category_id'] == 78059088)
            {
                $arrBantCake[]=$item;

            }
            if($item['category_id'] == 17036892)
            {
                $arrAnother[]=$item;

            }
            if($item['category_id'] == 17029520)
            {
                $arr6[]=$item;

            }
            if($item['category_id'] == 17035163)
            {
                $arr7[]=$item;

            }

            if($item['category_id'] == 17035163 || $item['category_id'] == 17029520 || $item['category_id'] == 17036892 || $item['category_id'] == 78059088 ||  $item['category_id'] == 78059066 || $item['category_id'] == 78286803 || $item['category_id'] == 55592804)
            {
                $newResponse[]=$item;
            }
        }
        /**
         * Уберем из массива повторяющиеся значения
         * Получим уникальные названия товаров
         * По каждому уникальному названию отфильтруем массив и заполним новый массив

        $tmpName  = $this->array_unique_key($arrOzonItems['result'], 'category_id');
        $bow =['type'=>$item['category_id']];
        foreach ($tmpName as $arr)
        {
            $name = $arr['category_id'];

            $bow[] = array_filter($arrOzonItems['result'], function ($value) use ($name) {

                return ($value["category_id"] == $name);
            });

        }
        //file_put_contents('attrVal.txt', json_encode($bow));
        $data = [
            'bant'=>$bow,
            'last_id' => $last_id,
            'category'=>self::createAttributes(),
        ];*/

        $data = [
            'bant'=>[
                $newResponse[0]['category_id']=>$newResponse,
                /*$arrBantCake[0]['category_id']=>$arrBantCake,
                $arrCollection[0]['category_id']=>$arrCollection,
                $arrBant[0]['category_id']=>$arrBant,
                $arrChocker[0]['category_id']=>$arrChocker,
                $arrAnother[0]['category_id']=>$arrAnother,
                $arr6[0]['category_id']=>$arr6,
                $arr7[0]['category_id']=>$arr7,*/
            ],
            'category'=>self::createAttributes(),
            'last_id'=>$last_id,
        ];
        //print_r($arrChocker[0]['category_id']);
        $view = view('shop')->with('data', $data);
        return new Response($view);

    }

    /**
     * Удаление дубликатов из массива по одному ключу
     * @param $array
     * @param $key
     * @return array
     */
    protected function array_unique_key($array, $key) {
        $tmp = $key_array = array();
        $i = 0;

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }
        return $tmp;
    }

    /**
     * @return array
     */public function createAttributes():array
{
    $type =[];
    $colors = [];
    $typeCode =[];
    $arrFull =[];
    $arrayMenu = [];
    $allItems = Cache::get('allItems');
    foreach ($allItems['result'] as $item)
    {
        $key ='';
        $val ='';
        foreach ($item['attributes'] as $attribute)
        {

            if($attribute['attribute_id'] === 8229) {
                if (!$attribute['values'][0]['value'] == '') {
                    $typeCode[]=$item['category_id'];
                    $key = $item['category_id'];
                    if($item['category_id'] === 78059088){
                        $type[] = 'Бант для волос детский';
                    }else{
                        /**
                         * Наименование категории на русском
                         */
                        $type[] = $attribute['values'][0]['value'];
                    }
                    $val = $attribute['values'][0]['value'];
                }

            }
            /**
             * Цвет
             */
            if($attribute['attribute_id'] === 10096)
            {
                if (!$attribute['values'][0]['value'] == ''){
                    $colors[]=$attribute['values'][0]['value'];
                }

            }

        }
        $arrFull[]=[
            $key => $val,
        ];

    }

    $array = array_unique($arrFull, SORT_REGULAR);

    $array = array_map("unserialize", array_unique(array_map("serialize", $array)));

    foreach ($array as $arr)
    {
        if(array_key_first($arr) != ""){
            $arrayMenu[] = [
                'category_id' => array_key_first($arr),
                'text_menu'=> array_values($arr),
            ];
        }

    }

    $result = [

        'colors'=>$colors,
        'typeCode'=>$arrayMenu,
    ];


    return $result;
}
    public function getResponseOzonArray(): array
    {
        return $this->responseOzonArray;
    }

}
