<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/Upload.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/ozon/ozon.php');

require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/TelegramBotHandMadeConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/OzonConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/YandexDiscConfiguration.php';

include($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$botApiConfiguration = TelegramBotHandMadeConfiguration::get_instance();
$yandexDiscConfiguration = YandexDiscConfiguration::get_instance();
$ozonConfiguration = OzonConfiguration::get_instance();

$yaDisk = new YaDisk();
$yaDisk->setToken($yandexDiscConfiguration->getYandexDiscToken());

$ozon = new Ozon($ozonConfiguration->getOzonToken(), $ozonConfiguration->ClientID);

$telegram = new Api($botApiConfiguration->getBotToken()); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["TOP-корзина"],["TOP-показы на карточке товара"],["TOP-всего показов"]]; //Клавиатура
$photo = $result["message"]['photo'];
$file = $result["message"]['document'];
$capture = $result['message']['caption'];

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

    }else{
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
    }

}else{

    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Только для меня']);

}
