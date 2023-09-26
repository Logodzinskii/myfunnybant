<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class StatGetOzon
{
    public static function getOzonCurlHtml($data, $method):array
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

    public static function attributeFilter($array, $value)
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

    public static function chpuGenerator($text)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($text);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z\.]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }

    public static  function getAllAction()
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

        if(strpos($html,'result') > 0){
            $countItemsSumm = 0;
            $arrayRes = json_decode($html, true);
            foreach ( $arrayRes['result'] as $countItems){

                $countItemsSumm += $countItems['participating_products_count'];

            }
            session(['countAction', $countItemsSumm]);
            $html = json_decode($html, true);
        }else{
            return $html;
        }

        return $html['result'];
    }

}
