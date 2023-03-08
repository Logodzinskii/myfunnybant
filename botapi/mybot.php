<?php


function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    $writeLog = new LogWiriter();
    if (!(error_reporting() & $errno)) {
        // Этот код ошибки не включён в error_reporting,
        // так что пусть обрабатываются стандартным обработчиком ошибок PHP
        return false;
    }

    // может потребоваться экранирование $errstr:
    $errstr = htmlspecialchars($errstr);

    switch ($errno) {
        case E_USER_ERROR:
            $writeLog->writeLog($errstr);
            exit();

        case E_USER_WARNING:
            //echo "<div class='reportMessage1' style='display: block; width: 30vw; height: auto; border: solid 1px pink; background: pink; color: black;'>";

            $writeLog->writeLog($errstr);
            //echo "</b></div>";
            //echo "<b>Пользовательское ПРЕДУПРЕЖДЕНИЕ</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            //echo "<div class='reportMessage1' style='display: block; width: 30vw; height: auto; border: solid 1px pink; background: lightgreen; color: black;'>";
            $writeLog->writeLog($errstr);
            //echo "</b></div>";
            break;

        default:
            //echo "<div class='reportMessage1' style='display: block; width: 30vw; height: auto; border: solid 1px pink; background: pink; color: black;'>";
            $writeLog->writeLog($errstr);
            //echo "</b></div>";
            break;
    }

    /* Не запускаем внутренний обработчик ошибок PHP */
    return true;
}

set_error_handler("myErrorHandler");

require_once ('autoloader.php');

date_default_timezone_set('asia/yekaterinburg');


$arr = json_decode(file_get_contents('php://input'), TRUE);

file_put_contents('botUnswer.txt', json_encode($arr),FILE_APPEND | LOCK_EX);


$user = UserConfiguration::get_instance();
$user->verifyUser($arr);

file_put_contents('botUnswer.txt', json_encode($user->verifyUser($arr)),FILE_APPEND | LOCK_EX);

$bot = new Bot();

//$resVerify = $user->verifyUser();

//if($resVerify === 'denied'){
//    $bot->addAnonimUser();
//}

if(!empty($arr['callback_query']['data'])){

    $metodParametrs = explode('#', $arr['callback_query']['data']);//разделим дату на части в первой название метода, после # параметры для вызова метода
    $writeLog = new LogWiriter();
    $writeLog->writeLog($arr['callback_query']['data'] . 'mybot.php str-73');
    $writeLog->writeLog($metodParametrs[0] . 'mybot.php str-74');

    switch ($metodParametrs[0]){
        case('updatestatus'):
            $parametrForMetod = explode('|', $metodParametrs[1]);
            try {
                //$user->verifyUser();
                if($parametrForMetod[0] == 'seller'){
                $user->updateStatusUser($parametrForMetod[0], $parametrForMetod[1]);
                //отправим продавцу новую клавиатуру
                $data = [
                    [
                        ['text' => '💵 Продажи за сегодня по артикулу'],
                        ['text' => '💰 Продажи за сегодня всего'],
                    ],
                    [
                        ['text' => '🏪 Завершить работу'],
                    ],
                ];
                $subject = 'Для внесения продаж отправь мне фотографию с описанием, например: 1, 200. Где 1 - количество проданного товара на фотографии, 200 - цена за единицу товара.';
                $bot->getKeyBoard($data, $subject, $parametrForMetod[1]);
                $bot->reply('Статус изменен:' . $parametrForMetod[1] . ' новый статус: ' . $parametrForMetod[0], $bot->getManagerId());
                }elseif($parametrForMetod[0] == 'newseller'){
                $user->updateStatusUser($parametrForMetod[0], $parametrForMetod[1]);
                //отправим продавцу новую клавиатуру
                $subject = 'До новых встреч!';
                        $data =
                            [
                                [
                                    ['text' => '/start'],
                                ],
                            ];
                $bot->getKeyBoard($data, $subject, $parametrForMetod[1]);
                $bot->reply('Статус изменен:' . $parametrForMetod[1] . ' новый статус: ' . $parametrForMetod[0], $bot->getManagerId());
                }
            }catch (Exception $e){
                trigger_error('mybot.php' . $e->getMessage());
            }
        break;
        case('delSaleitems'):
            try {
                //$user->verifyUser();
                $parametrForMetod = explode('|', $metodParametrs[1]);

                     $bot->delSaleItems($parametrForMetod[1], $arr['callback_query']['message']['chat']['id']);
                     //$arr['callback_query']['message']['message_id']
                     //$bot->reply('asdas', 645879928);
                     if($bot instanceof Bot){
                         $writeLog->writeLog('Bot - '.$parametrForMetod[1]);
                     }else{
                         $writeLog->writeLog('No Bot - '.$parametrForMetod[1]);
                     }

            }catch (Exception $e){
                trigger_error('CallBackQuery 45' . $e->getMessage());
            }
        break;
        case('updateCat'):
            //$user->verifyUser();
                $parametrForMetod = explode('|', $metodParametrs[1]);
            $bot->updateCat($parametrForMetod[0],$parametrForMetod[1], $arr['callback_query']['message']['chat']['id']);

            break;
        case('updateDate'):
            $parametrForMetod = explode('|', $metodParametrs[1]);
            $bot->updateDate($parametrForMetod[0], $arr['callback_query']['message']['chat']['id']);
            break;
        case('addSaleToAnotherSeller'):
            //$user->verifyUser();
            $parametrForMetod = explode('|', $metodParametrs[1]);
            $bot->addSaleToAnotherSeller($metodParametrs[1], $arr['callback_query']['message']['chat']['id']);
            break;
            case('insertSalesForSeller'):
                //$user->verifyUser();
                $parametrForMetod = explode('|', $metodParametrs[1]);
                $bot->insertSalesForSeller($parametrForMetod[0], $parametrForMetod[2], $arr['callback_query']['message']['chat']['id']);
                break;
                case('showReportAnonotherDay'):
                    //$user->verifyUser();
                    $parametrForMetod = explode('|', $metodParametrs[0]);
                    $report = new Report();
                    $bot->reply($report->sumAllSeller($arr['callback_query']['message']['chat']['id'], $metodParametrs[1]),$arr['callback_query']['message']['chat']['id']);
                    break;
        case('sumAllSellerByMonth'):
            $parametrForMetod = explode('|', $metodParametrs[0]);
            $report = new Report();
            $bot->reply($report->sumAllSellerByMonth($metodParametrs[1]),$arr['callback_query']['message']['chat']['id']);
            break;

    }
    exit();

}elseif(!empty($arr['message']['text'])){
    try{
        //$user->verifyUser();
        $bot->executeCommandUser($arr['message']['text']);
        $writeLog = new LogWiriter();
        $writeLog->writeLog($arr['message']['text']);
    }catch (Exception $e){
        trigger_error('myBot: 134' . $e->getMessage());
    }

    exit();
}elseif (!empty($arr['message']['photo'])){
    try{
        //$user->verifyUser();

        $image = new ProcessingImage($arr['message']['photo'], $arr['message']['caption'], $bot);
        $image->writeAndSaveImageSalesToDb();

        //$bot->sendButtons($arr['message']['chat']['id'], $image->writeAndSaveImageSalesToDb(), 'Запись внесена');

        $writeLog = new LogWiriter();
        $writeLog->writeLog($arr['message']['caption']);
    }catch (Exception $e){
        trigger_error('myBot: 151' . $e->getMessage());
    }

    }else{

    $writeLog = new LogWiriter();
    $writeLog->writeLog('Ничего не пришло');
    exit();
}
