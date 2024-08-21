<?php

namespace App\Http\Controllers\yandex;

use App\Http\Controllers\Controller;
use App\Models\OzonShop;
use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

class YandexYmlGenerator extends Controller
{
    public function createYmlFile()
    {
            $out ='<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            $out .='<yml_catalog date="'.date('Y-m-d H:i').'">'."\r\n";
            $out .='<shop>' . "\r\n";

            // Короткое название магазина, должно содержать не более 20 символов
            $out .='<name>Myfunnybant</name>' . "\r\n";

            // Полное наименование компании, владеющей магазином
            $out .='<company>Myfunnybant</company>' . "\r\n";

            // URL главной страницы магазина
            $out .='<url>https://myfunnybant.ru/</url>' . "\r\n";

            // Список курсов валют магазина
            $out .='<currencies>' . "\r\n";
            $out .='<currency id="RUR" rate="1"/>' . "\r\n";
            $out .='</currencies>' . "\r\n";

            // Список категорий магазина:

            $category =OzonShopItem::where('id','>','0')
                ->groupBy('type')
                ->get();

            $out .='<categories>' . "\r\n";
            foreach ($category as $key => $row) {

                $out .='<category id="' . $key . '">' . $row->type . '</category>' . "\r\n";

            }

            $out .='</categories>' . "\r\n";

            // Вывод товаров:


            $prods = OzonShopItem::where('id','>','0')
                ->get();

            $out .='<offers>' . "\r\n";
            foreach ($prods as $row) {
                $out .='<offer id="' . $row->id . '">'."\r\n";

                // URL страницы товара на сайте магазина
                $out .='<url>https://myfunnybant.ru/shop/' . OzonShop::select('url_chpu')->where('ozon_id','=',$row->ozon_id)->firstOrFail()->url_chpu . '</url>'."\r\n";

                // Цена, предполагается что в БД хранится цена и цена со скидкой

                $out .= '<price>' . StatusPriceShopItems::select('action_price')->where('ozon_id', '=' , $row->ozon_id )->first()->action_price . '</price>' . "\r\n";
                $out .= '<oldprice>' . StatusPriceShopItems::select('price')->where('ozon_id','=',$row->ozon_id)->first()->price . '</oldprice>' . "\r\n";


                // Валюта товара
                $out .= '<currencyId>RUR</currencyId>' . "\r\n";

                // ID категории
                $out .= '<categoryId>' . 1 . '</categoryId>' . "\r\n";

                // Изображения товара, до 10 ссылок
                $out .= '<picture>'.json_decode($row->images,true)[0]['file_name'].'</picture>' . "\r\n";
                $out .= '<picture>'.json_decode($row->images,true)[1]['file_name'].'</picture>' . "\r\n";
                $out .= '<picture>'.json_decode($row->images,true)[2]['file_name'].'</picture>' . "\r\n";
                // Название товара
                $out .= '<name>'.$row->header.'</name>' . "\r\n";

                // Описание товара, максимум 3000 символов
                $out .= '<description><![CDATA[' . stripslashes($row->description) . ']]></description>' . "\r\n";
                $out .= '</offer>' . "\r\n";
            }

            $out .= '</offers>' . "\r\n";
            $out .= '</shop>' . "\r\n";
            $out .= '</yml_catalog>' . "\r\n";

            /*header('Content-Type: text/xml; charset=utf-8');*/

           
                Storage::disk('yml')->put('/feed_01.yml', $out);
                echo $out;
    }

}