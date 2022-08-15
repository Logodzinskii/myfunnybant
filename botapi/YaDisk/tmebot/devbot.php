<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/Upload.php');
//include_once($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/ozon/ozon.php');

require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/TelegramBotHandMadeConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/OzonConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/YandexDiscConfiguration.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/Report.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/Ozon.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/YaDisc.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Controller/CallBackController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Controller/TextControler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/DateBase.php';

include($_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$botApiConfiguration = TelegramBotHandMadeConfiguration::get_instance();
$yandexDiscConfiguration = YandexDiscConfiguration::get_instance();
$ozonConfiguration = OzonConfiguration::get_instance();
$db = DateBase::get_instance();

$report = new Report();

$yaDisk = new YaDisk();
$yaDisk->setToken($yandexDiscConfiguration->getYandexDiscToken());

$ozon = new Ozon();

$telegram = new Api($botApiConfiguration->getBotToken()); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
$message_id = $result["message"]["message_id"];
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$path = $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/';
$db->setUserChatId($chat_id);

$photo = $result["message"]['photo'];
$file = $result["message"]['document'];
$capture = $result['message']['caption'];
/**
 * @param string $callBack
 * Например: type|Материалы
 */
$callBack = $result['callback_query']['data'];

if(isset($callBack))
{
    $arrayCallBackData = [
        'month'=>'monthResponse',
        'type'=>'typeResponse',
        'location'=>'locationRespons',
        'delete'=>'deleteExpenses',
    ];
    $callBackController = new CallBackController($callBack, $db->getConnection());
    $callBackControllerMethod = explode("|", $callBack);
    $callBackControllerMethod = $callBackControllerMethod[0];
    $callBackControllerMethod = $arrayCallBackData[$callBackControllerMethod];
    $chat_id = $result['callback_query']['message']['chat']['id'];
    $chat_id = ['chat_id'=>$chat_id];

    $telegram->sendMessage(array_merge($chat_id, $callBackController->$callBackControllerMethod()));

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

    if(isset($text))
    {
        $chat_id = [ 'chat_id' => $chat_id];

        $textController = new TextControler($text);
        $textRoutArray =
            [
                '/start' => 'startButtonTextController',
                'Внести Расходы'=>'executionChoiceMonth',
                'Пришли расходы' => 'showExpensesController',
                'TOP-Ozon'=>'choiceOzonTop',
                'В начальное меню'=>'startButtonTextController',
                //'TOP-корзина'=>'topOzonCart',
                //'TOP-показы на карточке товара'=>'topOzonCart',
                //'TOP-всего показов'=>'topOzonCart',
            ];
        if(array_key_exists($text, $textRoutArray))
        {
            $textControllerMethod = $textRoutArray[$text];

            $telegram->sendMessage(array_merge($chat_id, $textController->$textControllerMethod()));

        }else
        {
            $textController = new TextControler($text);

            $textControllerMethod = $textController->checkTextRegular();//ozonShowItem
            file_put_contents('checkTextRegular.txt', $textControllerMethod);
            if($textControllerMethod === 'not found')
            {
                $telegram->sendMessage(array_merge($chat_id['chat_id'], ['text'=> $textControllerMethod]));
            }

            $responseTextControllerArray = $textController->$textControllerMethod();

            if(array_key_exists('sendPhoto', $responseTextControllerArray))
            {
                file_put_contents('checkTextRegular.txt', $chat_id['chat_id'] . '|' . $responseTextControllerArray['sendPhoto']['photo'] . '|' . $responseTextControllerArray['sendPhoto']['caption']);

                $telegram->sendPhoto(
                    [
                        'chat_id' => $chat_id['chat_id'],
                        'photo'=> $responseTextControllerArray['sendPhoto']['photo'],
                        'caption'=> $responseTextControllerArray['sendPhoto']['caption'],
                    ]
                );

            }elseif(array_key_exists('sendMessage', $responseTextControllerArray))
            {

                $telegram->sendMessage(
                    [
                        'chat_id' => $chat_id['chat_id'],
                        'text' => $responseTextControllerArray['sendMessage']['text'],
                    ]);
            }elseif(array_key_exists('inline_keyboard', $responseTextControllerArray))
            {
                $telegram->sendMessage(
                    [
                        'chat_id' => $chat_id['chat_id'],
                        'text' => $responseTextControllerArray['inline_keyboard']['text'],
                        'reply_markup' => $responseTextControllerArray['inline_keyboard']['reply_markup'],
                    ]);
            }

        }

    }


    if(isset($result["message"]["location"]))
    {
        file_put_contents('location.txt', $result["message"]["location"]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Пришли сумму расхода. Пример: Расход-120']);
    }


}else{

    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Только для меня']);

}
