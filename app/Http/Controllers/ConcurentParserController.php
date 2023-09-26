<?php

namespace App\Http\Controllers;

use App\Models\OzonShop;
use Illuminate\Http\Request;

class ConcurentParserController extends Controller
{
    public function index()
    {
        $ch = curl_init('https://bantikland.ru/shop/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }
    public function getUrl()
    {
        $res = OzonShop::all();
        foreach ($res as $url)
        {
            echo '"'.'https://www.myfunnybant.ru/shop/' . $url->url_chpu . '",' . PHP_EOL;
        }

    }
}
