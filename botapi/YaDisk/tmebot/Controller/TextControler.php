<?php

class TextControler
{
    protected $ozon;

    protected string $text;
    public function __construct($text)
    {
        $this->text = $text;
        $this->ozon = new Ozon();
    }
    public function executionChoiceMonth()
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
        return ['text' => 'Выберите месяц', 'reply_markup' => $encodedKeyboard ];
    }

    public function startButtonTextController()
    {
        $keyboard =[
            'keyboard'=>[
                [
                    ['text' => 'TOP-корзина'],
                    ['text' => 'TOP-показы на карточке товара'],
                    ['text' => 'TOP-всего показов'],
                ],
                [
                    ['text' => 'Расходы'],
                ],
            ],
            "one_time_keyboard" => false,
            "resize_keyboard" => true


        ];
        $encodedKeyboard = json_encode($keyboard);
        return ['text' => 'Ok', 'reply_markup' => $encodedKeyboard];
    }

    public function checkTextRegular()
    {
        $arrRegular =
            [
                'method'=>'/^[TOP]+(-)+[а-я]+/',
                'ozonShowIitem'=>'/^[a-zA-z]*-[0-9]*-[0-9]*/',
            ];

        foreach ($arrRegular as $reg=>$val)
        {

            if(preg_match($val, $this->text) > 0)
            {
                return $reg;

            }else{
                return 'not found';
            }
        }
    }

    public function ozonShowItem()
    {
        $data = '{
                    "offer_id": [
                        "'.strtolower($this->text).'"
                    ],
                    "product_id": [],
                    "sku": []
                }';
        $res = json_decode($this->ozon->showItemArticle($data), true);
        if(is_array($res) && array_key_exists('img', $res)){

            return [
                'sendPhoto'=>[
                    'photo' => $res['img'],
                    'caption' => $res['name'] . ' На складе ' . $res['caption'] . '; цена до скидок - ' . $res['old_price'] . '; цена со скидкой ' . $res['price'] . '; Итого со всеми скидками (акции) ' . $res['marketing_price'] .  ' статус - ' .  $res['state_name'] . ' ' . $res['state_description'] . ' ' . $res['state_tooltip'],
                ]
            ];

        }else{

            return [
                'sendMessage' =>[
                    'text' => 'такого товара нет'
                ]
            ];

        }
    }
}
