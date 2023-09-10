<?php

namespace App\Http\Controllers;

use App\Events\ClickOzonLink;
use App\Models\Offers;
use App\Models\OzonShop;
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
    public function showItem(Request $request, $offer_chpu = null)
    {
        if($offer_chpu == null){

            $offer_id = $request->id;
        }else{
            $offer_id = OzonShop::where('url_chpu', '=', $offer_chpu)->first();
            $offer_id = $offer_id->ozon_id;
        }

        //ClickOzonLink::dispatch($offer_id, $request);
        /**
         * Получу информацию о ценах
         */
        $data = '{
            "offer_id":  "",
            "product_id": "269542382",
            "sku": 0
        }';
        $method = '/v2/product/info';
        /**
         * получу всю информацию о товаре
         */
        $dataAttribute = '{
                "filter": {
                    "product_id": [
                        "'.$offer_id.'"
                    ],
                    "visibility": "ALL"
                },
                "limit": 100,
                "last_id": "okVsfA==«",
                "sort_dir": "ASC"
            }';
        $methodAttribute = '/v3/products/info/attributes';
        $ozonResult = $this->curl_request_ozon($dataAttribute, $methodAttribute);
        if(isset($ozonResult['result']))
        {
            $res = new Offers([
                'name'=>$ozonResult['result'][0]['name'],
                'images'=>$ozonResult['result'][0]['images'],
                'attributes'=>[
                    'Ширина'=>$ozonResult['result'][0]['width'] . 'мм',
                    'Высота'=>$ozonResult['result'][0]['height'] . 'мм',
                    'Глубина'=>$ozonResult['result'][0]['depth'] . 'мм',
                    'Материал'=>($this->attributeFilter($ozonResult['result'][0]['attributes'], 5309))[0] ?? null,
                    'Цвет'=>($this->attributeFilter($ozonResult['result'][0]['attributes'], 10096))[0] ?? null,
                    'Описание'=>($this->attributeFilter($ozonResult['result'][0]['attributes'], 4191))[0] ?? null,
                ],
                'colors'=>$ozonResult['result'][0]['offer_id'],
            ]);
        }else{
            return redirect('404');
        }

        //return $res;
        return view('item', ['res'=>$res]);

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
            $like = OzonShop::where('ozon_id', $off['id'])->get();
            $like =$like[0]->like_count;
                $offers[] = new Offers([
                    'name'=>$off['name'],
                    'images'=>$off['images'],
                    //'attributes'=>$off['attributes'][0]['attribute_id'],
                    'attributes'=>[
                        'id'=>$off['id'],
                        'category'=> $off['category_id'],
                        'type'=>$this->attributeFilter($off['attributes'], 8229),
                        'header'=>$this->attributeFilter($off['attributes'], 4180),
                        'description'=>$this->attributeFilter($off['attributes'], 4191),
                        'colors'=>$this->attributeFilter($off['attributes'], 10096),
                        'like'=>$like,
                    ],
                    'colors'=>$off['offer_id']
                ]);
        }
        //return $arrOzonItems['result'];
        //$view = view('main.index')->with('data', $offers);
        return view('main.index', ['data'=>[$offers]]);

    }

    protected function attributeFilter($array, $value)
    {
        $typeAttr=[];
        $type = array_filter($array, function($item) use ($value) {
            return $item['attribute_id'] == $value;
        });
        foreach ($type as $item)
        {
            foreach ($item['values'] as $res)
            {
                $typeAttr[] = $res['value'];
            }
        }
        return $typeAttr;
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

    public function getResponseOzonArray(): array
    {
        return $this->responseOzonArray;
    }

}
