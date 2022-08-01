<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/Upload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/ozon/ozon.php');

require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/TelegramBotHandMadeConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/OzonConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/YandexDiscConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/Report.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/DateBase.php';

include($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$botApiConfiguration = TelegramBotHandMadeConfiguration::get_instance();
$yandexDiscConfiguration = YandexDiscConfiguration::get_instance();
$ozonConfiguration = OzonConfiguration::get_instance();
$db = DateBase::get_instance();

$report = new Report($db->getConnection());

$yaDisk = new YaDisk();
$yaDisk->setToken($yandexDiscConfiguration->getYandexDiscToken());

$ozon = new Ozon($ozonConfiguration->getOzonToken(), $ozonConfiguration->ClientID);

$telegram = new Api($botApiConfiguration->getBotToken()); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
$message_id = $result["message"]["message_id"];
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$path = $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/';
$keyboard =[

    [
        ['text' => 'TOP-корзина'],
        ['text' => 'TOP-показы на карточке товара'],
        ['text' => 'TOP-всего показов'],
    ],
    [
        ['text' => 'Расходы'],
    ],
]; //Клавиатура
$photo = $result["message"]['photo'];
$file = $result["message"]['document'];
$capture = $result['message']['caption'];
$callBack = $result['callback_query']['data'];

if(isset($callBack))
{

    $chat_id = $result['callback_query']['message']['chat']['id'];

    if(strpos($callBack, "th")>0)
    {
        $month = explode('|',$callBack);

        if($month[1] == 13)
        {
            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("Y-m-d");

            file_put_contents('month.txt', $today);

            $keyboard = [
                'inline_keyboard' =>
                    [
                        [
                            ['text'=> 'Материалы', 'callback_data' => 'type|Материалы'],
                            ['text'=> 'Аренда', 'callback_data' => 'type|Аренда'],

                        ],

                    ],
            ];
            $encodedKeyboard = json_encode($keyboard);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Выберите тип расходов', 'reply_markup' => $encodedKeyboard]);


        }elseif($month[1] == 0){

            $keyboard = [
                'inline_keyboard' =>
                    [
                        [
                            ['text'=> 'Январь', 'callback_data' => 'month|1'],
                            ['text'=> 'Февраль', 'callback_data' => 'month|2'],
                            ['text'=> 'Март', 'callback_data' => 'month|3'],
                        ],
                        [
                            ['text'=> 'Апрель', 'callback_data' => 'month|4'],
                            ['text'=> 'Май', 'callback_data' => 'month|5'],
                            ['text'=> 'Июнь', 'callback_data' => 'month|6'],
                        ],
                        [
                            ['text'=> 'Июль', 'callback_data' => 'month|7'],
                            ['text'=> 'Август', 'callback_data' => 'month|8'],
                            ['text'=> 'Сентябрь', 'callback_data' => 'month|9'],
                        ],
                        [
                            ['text'=> 'Октябрь', 'callback_data' => 'month|10'],
                            ['text'=> 'Ноябрь', 'callback_data' => 'month|11'],
                            ['text'=> 'Декабрь', 'callback_data' => 'month|12'],
                        ],
                        [
                            ['text'=> 'Сегодня', 'callback_data' => 'month|13'],
                        ]
                    ],
            ];

            $encodedKeyboard = json_encode($keyboard);

            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Выберите месяц', 'reply_markup' => $encodedKeyboard ]);

        }else{


            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("Y-m-d");
            $date = $y.'-'.$month[1].'-01';
            file_put_contents('month.txt', $date);

            $keyboard = [
                'inline_keyboard' =>
                    [
                        [
                            ['text'=> 'Материалы', 'callback_data' => 'type|Материалы'],
                            ['text'=> 'Аренда', 'callback_data' => 'type|Аренда'],

                        ],

                    ],
            ];
            $encodedKeyboard = json_encode($keyboard);
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Выберите тип расходов', 'reply_markup' => $encodedKeyboard]);
        }



    }elseif((strpos($callBack, "pe")>0))
    {
        $type = explode('|',$callBack);

        file_put_contents('type.txt', $type[1]);
        $keyboard = [
            'inline_keyboard' =>
                [
                    [
                        ['text'=> 'Да', 'callback_data' => 'locations|yes'],
                        ['text'=> 'Нет', 'callback_data' => 'locations|no'],

                    ],

                ],
        ];
        $encodedKeyboard = json_encode($keyboard);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Отправить локацию?', 'reply_markup' => $encodedKeyboard]);
    }
    elseif((strpos($callBack, "tio")>0))
    {
        $loc = explode('|',$callBack);
        if($loc[1] === 'no')
        {
            file_put_contents('location.txt', '{"latitude":0,"longitude":0}');

            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Сумма расходов']);
        }else{
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Отправь геопозицию']);
        }

    }elseif ((strpos($callBack, "teExp")) > 0)
    {
        $id = explode('|',$callBack);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $report->deleteExpenses($id)]);

    }


}

if($chat_id == $botApiConfiguration->getManagerId() || $botApiConfiguration->getManagerIdSecond())
{

    if (!is_null($photo))
    {
        $token = $botApiConfiguration->getBotToken();

        if (!empty($result['message']['photo'])) {
            //$photo = array_pop($result['message']['photo']);

            $ch = curl_init('https://api.telegram.org/bot' . $token . '/getFile');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('file_id' => $result["message"]['photo'][3]['file_id']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($res, true);
            if ($res['ok']) {
                $src  = 'https://api.telegram.org/file/bot' . $token . '/' . $res['result']['file_path'];
                $dest = $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/upload/'. basename($src);

                if(!copy($src, $dest))
                {
                    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Файл не записан на сервер']);
                }

                $res = $yaDisk->saveFile(basename($src));
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $res]);
            }
        }
        return false;
    }
    if (!is_null($file))
    {
        $token = $botApiConfiguration->getBotToken();

        if (!empty($result['message']['document'])) {

            $ch = curl_init('https://api.telegram.org/bot' . $token . '/getFile');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('file_id' => $result["message"]['document']['file_id']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($res, true);
            if ($res['ok']) {
                $src  = 'https://api.telegram.org/file/bot' . $token . '/' . $res['result']['file_path'];
                $dest = $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/upload/'. basename($src);

                if(!copy($src, $dest))
                {
                    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Файл не записан на сервер']);
                }

                $res = $yaDisk->saveFile(basename($src));
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $res]);
            }
        }
        return false;
    }

    if ($text != '/start' && strpos($text, "/") > 0) {

        $res = $yaDisk->createPath($text);

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $res]);

    }elseif ($text == '/start')
    {
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Ok', 'reply_markup' => $reply_markup ]);
    }
    elseif(preg_match('/^[TOP]+(-)+[а-я]+/', $text) > 0) {

        $request = [
            'hits_tocart' => 'TOP-корзина',
            'hits_view_pdp' => 'TOP-показы на карточке товара',
            'hits_view' => 'TOP-всего показов',
        ];

        $strRes = array_search($text, $request);

        $resHit = json_decode($ozon->hitToCart($strRes),true);

        foreach ($resHit as $hit)
        {
            $data = '{
                    "offer_id": [

                    ],
                    "product_id": [],
                    "sku": ["'.strtolower($hit['id']).'"]
                }';

            $resImg = json_decode($ozon->showItemArticle($data), true);
            if(is_array($resImg) && array_key_exists('img', $resImg)){
                $telegram->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => $resImg['img'],
                    'caption' => $resImg['name'] . ' На складе ' . $resImg['caption'] . '; цена до скидок - ' . $resImg['old_price'] . '; цена со скидкой ' . $resImg['price'] . '; Итого со всеми скидками (акции) ' . $resImg['marketing_price'] .  ' статус - ' .  $resImg['state_name'] . ' ' . $resImg['state_description'] . ' ' . $resImg['state_tooltip'] . $hit['name'] . '. Просмотров всего-' . $hit['hits_view'] . '. В корзину - ' . $hit['hits_to_cart']
                ]);
            }else{
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'такого товара нет']);
            }
            //$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $hit['name'] . '. Просмотров всего-' . $hit['hits_view'] . '. В корзину - ' . $hit['hits_to_cart']]);
        }

    }elseif($text === 'Расходы')
    {
        $keyboard = [
            'inline_keyboard' =>
                [
                    [
                        ['text'=> 'Сегодня', 'callback_data' => 'month|13'],
                        ['text'=> 'Другой месяц', 'callback_data' => 'month|0'],

                    ],
                ],
        ];

        $encodedKeyboard = json_encode($keyboard);

        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Выберите месяц', 'reply_markup' => $encodedKeyboard ]);
    }elseif(strpos($text,'-')>0){
        $data = '{
                    "offer_id": [
                        "'.strtolower($text).'"
                    ],
                    "product_id": [],
                    "sku": []
                }';
        $res = json_decode($ozon->showItemArticle($data), true);
        if(is_array($res) && array_key_exists('img', $res)){
            $telegram->sendPhoto([
                'chat_id' => $chat_id,
                'photo' => $res['img'],
                'caption' => $res['name'] . ' На складе ' . $res['caption'] . '; цена до скидок - ' . $res['old_price'] . '; цена со скидкой ' . $res['price'] . '; Итого со всеми скидками (акции) ' . $res['marketing_price'] .  ' статус - ' .  $res['state_name'] . ' ' . $res['state_description'] . ' ' . $res['state_tooltip']
            ]);
        }else{
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'такого товара нет']);
        }
    }elseif($text >= 10){

        $arrTodb = [
            'saller'=> $chat_id,
            'name_expens'=>file_get_contents('type.txt'),
            'totalPrice'=>$text,
            'date'=>file_get_contents('month.txt'),
            'location'=>file_get_contents('location.txt'),
        ];

        $res = $report->addExpenses($arrTodb);
        //$res = 1;

        $keyboard = [
            'inline_keyboard' =>
                [
                    [
                        ['text'=> 'Удалить?', 'callback_data' => 'deleteExpenses|'. $res],
                    ],
                ],
        ];

        $encodedKeyboard = json_encode($keyboard);

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Расход за месяц - ' .  file_get_contents('month.txt') . ' в категорию - ' . file_get_contents('type.txt') . ' на сумму - ' . $text.' занесен' . 'id - ' . $res, 'reply_markup' => $encodedKeyboard]);
        file_put_contents('type.txt', 0);
        file_put_contents('month.txt', 0);
        file_put_contents('location.txt', 0);
    }
    if(isset($result["message"]["location"]))
    {
        file_put_contents('location.txt', $result["message"]["location"]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Пришли сумму расхода']);
    }


}else{

    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Только для меня']);

}

