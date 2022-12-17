<?php

class CallBackQuery
{
    private $user, $bot, $arr;
    public function __construct($user,$bot,$arr)
    {
        $this->user = $user;
        $this->bot = $bot;
        $this->arr = $arr;
    }
    public function parseCallback()
    {
        try{
                    $metodParametrs = explode('#', $this->arr['callback_query']['data']);//разделим дату на части в первой название метода, после # параметры для вызова метода

        }catch (Exception $e){
                    trigger_error($e->getMessage() . 'CallBackQuery18');
        }

        switch ($metodParametrs[0]){
            case('updatestatus'):
                $parametrForMetod = explode('|', $metodParametrs[1]);
                try {
                    $this->user->updateStatusUser($parametrForMetod[0], $parametrForMetod[1]);
                }catch (Exception $e){
                    trigger_error($e->getMessage());
                }

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
                $subject = 'Для внесения продаж отправь мне фотографию с описанием, например: 1, 200, место продажи. Где 1 - количество проданного товара на фотографии, 200 - цена за единицу товара, место продажи';
                $this->bot->getKeyBoard($data, $subject, $parametrForMetod[1]);
                $this->bot->reply('Статус изменен:' . $parametrForMetod[1] . ' новый статус: ' . $parametrForMetod[0], $this->bot->managerId);
                     break;
            case('delSaleitems'):
                $parametrForMetod = explode('|', $metodParametrs[1]);
                try {
                     $this->bot->delSaleItems($parametrForMetod[1]);
                     $this->bot->reply('Запись' . $metodParametrs[1] . 'удалена');
                }catch (Exception $e){
                    trigger_error('CallBackQuery 45' . $e->getMessage());
                }
                break;
            case 'updateCat':

                //$parametrForMetod = explode('|', $metodParametrs[1]);
                try {
                    //$this->bot->updateCat($parametrForMetod[0], $parametrForMetod[1]);
                    $this->bot->reply('ads');
                }catch (Exception $e){
                    trigger_error($e->getMessage());
                }
                break;
        }
    }
}
