<?php

class CallBackController
{
    protected string $callBackData;


    public function __construct($callBackData)
    {
        $this->callBackData = $callBackData['data'];


    }

    public function monthResponse()
    {
        $month = explode('|', $this->callBackData);

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
            return ['text' => 'Выберите тип расходов', 'reply_markup' => $encodedKeyboard];


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

            return [ 'text' => 'Выберите месяц', 'reply_markup' => $encodedKeyboard ];

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
            return ['text' => 'Выберите тип расходов', 'reply_markup' => $encodedKeyboard];
        }
    }

    public function typeResponse()
    {
        $type = explode('|',$this->callBackData);

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
        return ['text' => 'Отправить локацию?', 'reply_markup' => $encodedKeyboard];
    }

    public function locationResponse()
    {
        $loc = explode('|',$this->callBackData);
        if($loc[1] === 'no')
        {
            file_put_contents('location.txt', '{"latitude":0,"longitude":0}');

            return ['text' => 'Сумма расходов'];
        }else{
            return ['text' => 'Отправь геопозицию'];
        }
    }

    public function deleteExp()
    {
        $id = explode('|',$this->callBackData);

        return $id[1];
    }
}
