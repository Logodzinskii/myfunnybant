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
}
