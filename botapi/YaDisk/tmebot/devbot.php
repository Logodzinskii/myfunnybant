<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/Upload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/ozon/ozon.php');

require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/TelegramBotHandMadeConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/OzonConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/YandexDiscConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/Report.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Controller/CallBackController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/DateBase.php';

include($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$arrayCallBackData = [
    'month'=>'monthResponse',
    'type'=>'typeResponse',
    'location'=>'locationResponse',
    'deleteExp'=>'deleteExp',
];

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
    /**
     * парсим CallBack и из массива @param $arrayCallBackData
     * по совпадающему ключу вызываем метод класса CallBackDataController
     */
    if(strpos($callBack, "th")>0)
    {

        $monthResponse = new CallBackController($callBack);
        $chat_id = ['chat_id'=>$chat_id];
        $telegram->sendMessage(array_merge($chat_id, $monthResponse->monthResponse()));


    }elseif((strpos($callBack, "pe")>0))
    {

        $typeResponse = new CallBackController($callBack);
        $chat_id = ['chat_id'=>$chat_id];
        $telegram->sendMessage(array_merge($chat_id, $typeResponse->typeResponse()));

    }
    elseif((strpos($callBack, "tio")>0))
    {

        $locationResponse = new CallBackController($callBack);
        $chat_id = ['chat_id'=>$chat_id];
        $telegram->sendMessage(array_merge($chat_id, $locationResponse->locationResponse()));


    }elseif ((strpos($callBack, "teExp")) > 0)
    {

        $deleteExp = new CallBackController($callBack);
        $chat_id = ['chat_id'=>$chat_id];
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $report->deleteExpenses($deleteExp->deleteExp())]);

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

