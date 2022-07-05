<?php

class Ozon
{
    public function showItemArticle($data)
    {
        define("OZONTOKEN", '4eae7b5c-142a-4930-9920-af3866bed6e2');

        define("CLIENT_ID", '302542');

        $clientId = CLIENT_ID; //айди шопа

        $apiKey = OZONTOKEN; // ключ апи

        $method = '/v2/product/info/list'; //метод запроса

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
        //return $html;
        $arr = (json_decode($html, true));
        if (isset($arr['result']['items'][0]['primary_image'])){
            return json_encode([
                'img' => $arr['result']['items'][0]['primary_image'],
                'caption' => $arr['result']['items'][0]['stocks']['present'],
                'old_price' => $arr['result']['items'][0]['old_price'],
                'price'=> $arr['result']['items'][0]['price'],
                'marketing_price'=>$arr['result']['items'][0]['marketing_price'],
                'name'=>$arr['result']['items'][0]['name'],
                'status'=>$arr['result']['items'][0]['status']['state_name'],
                'state_description'=>$arr['result']['items'][0]['status']['state_description'],
                'state_tooltip'=>$arr['result']['items'][0]['status']['state_tooltip'],
            ]);
        }else{
            return 'error';
        }

    }

    public function hitToCart($request)
    {
        define("OZONTOKEN", '4eae7b5c-142a-4930-9920-af3866bed6e2');

        define("CLIENT_ID", '302542');

        $clientId = CLIENT_ID; //айди шопа

        $apiKey = OZONTOKEN; // ключ апи

        $method = '/v1/analytics/data'; //метод запроса

#////тело запроса///#
        $data = new DateTime('now');
        $today = $data->format('Y-m-d');
        $oneWeekEarly = new DateTime('- 1 week');
        $oneWeekEarly = $oneWeekEarly->format('Y-m-d');
        $data = '{
            "date_from": "'. $oneWeekEarly .'",
            "date_to": "'. $today .'",
            "metrics": [
                "hits_view_search",
                "'.$request.'"
            ],
            "dimension": [
                "sku",
                "week"
            ],
            "filters": [],
            "sort": [
                {
                    "key": "'.$request.'",
                    "order": "DESC"
                }
            ],
            "limit": 10,
            "offset": 0
        }';
#////////#

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
        //return $html;
        $arr = (json_decode($html, true));
        if(array_key_exists('metrics', $arr['result']['data'][0])){
            $res=[];
            foreach($arr['result']['data'] as $hit)
            {
                if($hit['metrics'][1] > 0)
                {
                    $res[]=['name'=>$hit['dimensions'][0]['name'],'id'=>$hit['dimensions'][0]['id'], 'hits_view'=>$hit['metrics'][0], 'hits_to_cart'=>$hit['metrics'][1] ];
                }
            }
            return json_encode($res);
        }else{
            return 'нет данных';
        }

    }

}