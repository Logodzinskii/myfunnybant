<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/Configuration/DateBase.php';
class CallBackController
{
    public string $callBackData;
    public $connections;

    public function __construct($callBackData, $connections)
    {
        $this->callBackData = $callBackData;
        $this->connections = $connections;
    }

    public function monthResponse()
    {
        file_put_contents( $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/tmp/monthResponse.txt', $this->callBackData);
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
                        ['text'=> 'Да', 'callback_data' => 'location|yes'],
                        ['text'=> 'Нет', 'callback_data' => 'location|no'],

                    ],

                ],
        ];
        $encodedKeyboard = json_encode($keyboard);
        return ['text' => 'Отправить локацию?', 'reply_markup' => $encodedKeyboard];
    }

    public function locationRespons()
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

    public function deleteExpenses()
    {
        $id = explode('|',$this->callBackData);

        $query = "DELETE FROM `expenses` WHERE `id` = ?";
        $params = [$id[1]];
        $stmt = $this->connections->prepare($query);
        $stmt->execute($params);
        return 'Удалено - ' . $id[1];

        $stmt = $this->connections->prepare( "DELETE FROM `expenses` WHERE id =:id" );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if( $stmt->rowCount() ){
            return 'Удалено - ' . $id[1];
        }else{
            return "Deletion failed";
        }

    }
}
