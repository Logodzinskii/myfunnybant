<?php

namespace App\Http\Controllers\yandex;

use App\Http\Controllers\Controller;
use App\Models\OzonShop;
use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YandexYmlGenerator extends Controller
{
    public function createYmlFile()
    {
        // Подключение к БД
        //$dbh = new PDO('mysql:dbname=db_name;host=localhost', 'логин', 'пароль');

        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $out .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\r\n";
        $out .= '<shop>' . "\r\n";

// Короткое название магазина, должно содержать не более 20 символов
        $out .= '<name>Myfunnybant</name>' . "\r\n";

// Полное наименование компании, владеющей магазином
        $out .= '<company>Myfunnybant</company>' . "\r\n";

// URL главной страницы магазина
        $out .= '<url>https://myfunnybant.ru/</url>' . "\r\n";

// Список курсов валют магазина
        $out .= '<currencies>' . "\r\n";
        $out .= '<currency id="RUR" rate="1"/>' . "\r\n";
        $out .= '</currencies>' . "\r\n";

// Список категорий магазина:
// id     - ID категории
// parent - ID родительской категории
// name   - Название категории
        //$sth = $dbh->prepare("SELECT `id`, `parent`, `name` FROM `category`");
        //$sth->execute();
        $category =OzonShopItem::where('id','>','0')
                    ->groupBy('type')
                    ->get();

        $out .= '<categories>' . "\r\n";
        foreach ($category as $key => $row) {

                $out .= '<category id="' . $key . '">' . $row->type . '</category>' . "\r\n";

        }

        $out .= '</categories>' . "\r\n";

// Вывод товаров:
        //$sth = $dbh->prepare("SELECT * FROM `prods`");
        //$sth->execute();
        //$prods = $sth->fetchAll(PDO::FETCH_ASSOC);

        $prods = OzonShopItem::where('id','>','0')
        ->get();

        $out .= '<offers>' . "\r\n";
        foreach ($prods as $row) {
            $out .= '<offer id="' . $row->id . '">' . "\r\n";

            // URL страницы товара на сайте магазина
            $out .= '<url>https://myfunnybant.ru/shop/' . OzonShop::where('ozon_id','=',$row->ozon_id)->get()[0]->url_chpu . '</url>' . "\r\n";

            // Цена, предполагается что в БД хранится цена и цена со скидкой

                $out .= '<price>' . StatusPriceShopItems::where('ozon_id','=',$row->ozon_id)->get()[0]->action_price . '</price>' . "\r\n";
                $out .= '<oldprice>' . StatusPriceShopItems::where('ozon_id','=',$row->ozon_id)->get()[0]->price . '</oldprice>' . "\r\n";


            // Валюта товара
            $out .= '<currencyId>RUR</currencyId>' . "\r\n";

            // ID категории
            $out .= '<categoryId>' . 1 . '</categoryId>' . "\r\n";

            // Изображения товара, до 10 ссылок
            $out .= '<picture>'.json_decode($row->images,true)[0]['file_name'].'</picture>' . "\r\n";
            $out .= '<picture>https://example.com/img/2.jpg</picture>' . "\r\n";

            // Название товара
            $out .= '<name>'.$row->header.'</name>' . "\r\n";

            // Описание товара, максимум 3000 символов
            $out .= '<description><![CDATA[' . stripslashes($row->description) . ']]></description>' . "\r\n";
            $out .= '</offer>' . "\r\n";
        }

        $out .= '</offers>' . "\r\n";
        $out .= '</shop>' . "\r\n";
        $out .= '</yml_catalog>' . "\r\n";

        header('Content-Type: text/xml; charset=utf-8');
        return $out;

    }
}
