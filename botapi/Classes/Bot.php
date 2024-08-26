<?php

class Bot extends Exception
{
    private $telegramm_id, $userName, $status, $dateAdd;
    private $url, $botToken, $botAPI;
    private $connect;
    private $managerId;


    public function __construct()
    {
        $telegramBotApiConfiguration = TelegramApiConfiguration::get_instance();
        $UserConfiguration = UserConfiguration::get_instance();
        $this->telegramm_id = $UserConfiguration->getTelegramId();
        $this->userName = $UserConfiguration->getFirstName();
        $this->status = $UserConfiguration->getStatus();
        $this->dateAdd = $UserConfiguration->getDateAdd();

        $this->url = $telegramBotApiConfiguration->getUrl();
        $this->botAPI = $telegramBotApiConfiguration->getBotAPI();
        $this->botToken =$telegramBotApiConfiguration->getBotToken();

        $this->managerId = $telegramBotApiConfiguration->getManagerId();
        $con = DateBase::get_instance();
        $this->connect = $con->getConnection();

        $this->report = new Report;
    }

    public function addAnonimUser()
    {

        try {
            $sth = $this->connect->prepare("INSERT INTO `users` SET `telegram_id` = :telegram_id, `first_name` = :first_name, `status` = :status, `dateadduser` = :dateadduser, `open_shop` = :open_shop");
            $sth->execute(['telegram_id' => $this->telegramm_id, 'first_name' => $this->userName,'status'=>'newseller', 'dateadduser'=>$this->dateAdd, 'open_shop' => 0]);
            // Получаем id вставленной записи
            $insert_id = $this->connect->lastInsertId();
            self:: reply('Добро пожаловать!' . $this->userName);
        }catch (PDOException $e){
            trigger_error("Bot.php select 40: " . $e->getMessage(), E_USER_WARNING);
            die();
        }

    }

    public function reply($message,$chatid=null){
        try{
           $chatid = $chatid ?? $this->telegramm_id;
           $message = $message ?? 'cod1';
            $this->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $chatid,
                    'text' => $message,
                )
            );
        }catch (PDOException $e){
            trigger_error("Bot.php reply:58 " . $e->getMessage(), E_USER_WARNING);
            die();
        }

    }

    public function sendPost($arr)
    {

        $this->sendTelegram(
            'sendPhoto',
            array(
                'chat_id' => $this->telegramm_id,
                'photo' => curl_file_create($_SERVER['DOCUMENT_ROOT'].'/VK/vkSettings/image/'. basename($arr['image'][0])),
                'caption' => $arr['capture'],
            )
        );
    }

    public function executeCommandUser($command)
    {
        $shop = new Shop();
        $shop->initializeShop('myfunnybantbot');
        switch ($command){
            case ("/start"):
                if ($this->status == 'newseller') {
                    if ($shop->getShopStatus() == 1) {
                        $this->reply('Добро пожаловать!');
                        $keyboard =
                                [
                                    [
                                        ['text' => '🙋 Приступить к работе'],
                                    ],
                                ];
                       // $this->getKeyBoard($keyboard, 'Запрос направлен, ожидайте');
                        }else {
                        $this->reply('Приём еще не открыт. Дождитесь открытия приема менеджером. Затем нажмите кнопку /start');
                            $keyboard =
                                [
                                    [
                                        ['text' => '/start'],
                                    ],
                                ];
                        }
                        //$this->getKeyBoard($keyboard, $subject);
                    }elseif($this->status == 'seller'){
                        $keyboard =
                            [
                                [
                                    ['text' => '💵 Продажи за сегодня по артикулу'],
                                    ['text' => '💰 Продажи за сегодня всего'],
                                ],
                                [
                                    ['text' => 'Продажи за другие даты'],
                                    ['text' => '🏪 Завершить работу'],
                                ],
                                [
                                    ['text' => 'Свернуть клавиатуру'],
                                    ['text'=>'За месяц'],
                                ],
                            ];
                    }elseif($this->status == 'manager'){
                        $keyboard =
                            [

                                [
                                    ['text' => 'Открыть прием'],
                                    ['text' => 'Закрыть прием'],
                                    ['text' => 'Посмотреть запрос'],
                                ],
                                [
                                    ['text' => '💵 Продажи за сегодня по артикулу'],
                                    ['text' => '💰 Продажи за сегодня всего'],
                                ],
                                [
                                    ['text' => '💰 Продажи всех продавцов'],
                                    ['text' => 'За месяц'],
                                ],
                                [
                                    ['text' => 'Продажи за другие даты'],
                                    ['text' => 'Пост из VK'],
                                ],
                                [
                                    ['text' => 'Свернуть клавиатуру'],
                                ],
                            ];
                    }

                    $this->getKeyBoard($keyboard);
                    break;
            case('Открыть прием'):
                if($this->status === 'manager'){
                    $this->reply($shop->openCloseShop(1));
                }
                break;
                case('Свернуть клавиатуру'):
                   $keyboard =
                            [
                                [
                                    ['text' => '/start'],
                                ],
                            ];


                    $this->getKeyBoard($keyboard);
                    break;
            case('Закрыть прием'):
                if($this->status === 'manager') {
                    $this->reply($shop->openCloseShop(0));
                }
                break;
            case('Посмотреть запрос'):

                $array= $shop->showNewSeller();

                if (count($array) > 0){
                foreach ($array as $k => $row){

                    $nameSeller = $row['first_name'];
                    $chatIdSeller = $row['telegram_id'];
                    $keyboard = [
                    'inline_keyboard' => [

                        [
                            ['text' => 'Принять на работу', 'callback_data' => 'updatestatus#seller|'.$chatIdSeller],
                            ['text' => 'Уволить с работы', 'callback_data' => 'updatestatus#newseller|'.$chatIdSeller],
                        ],
                    ]
                ];

                    $reply_markup = json_encode($keyboard);

                    $this->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $this->telegramm_id,
                            'text' => 'Поступил запрос: ' . $nameSeller ,
                            'reply_markup'=>$reply_markup,
                        ));
                        $data = $nameSeller;
                        //file_get_contents($this->botToken . "/sendMessage?{$data}&reply_markup={$keyboard}");
                        //$this->sendButtons($this->telegramm_id, $keyboard, 'Заявка от - ' . $nameSeller);
                }
                    }else{
                    $this->reply('no');
                }

                break;
                case("🙋 Приступить к работе"):

                    $this->reply('Запрос принят');
                    $this->reply('Поступил запрос на прием - '. $this->userName, $this->managerId);

                    //parent :: updateStatusUser('newseller', $this->telegramm_id);
                    break;
                case("💵 Продажи за сегодня по артикулу"):

                $this->reply($this->report->toDay($this->telegramm_id));

                break;
                case("💰 Продажи за сегодня всего"):

                $this->reply($this->report->sumToDay($this->telegramm_id));

                    break;
                     case("💰 Продажи всех продавцов"):

                $this->reply($this->report->sumAllSeller($this->telegramm_id));

                    break;
                    case("Продажи за другие даты"):

                $arrDate = json_decode($this->report->enotherDay());

                $keyboard = [
                    'inline_keyboard' =>

                            $arrDate,

                ];

                $reply_markup = json_encode($keyboard);

                    $this->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $this->telegramm_id,
                            'text' => 'Выберите дату для отчета',
                            'reply_markup'=>$reply_markup,
                        ));

                break;
                case ("Пост из VK"):

                $url = 'https://myfunnybant.ru/VK/postcreater.php';
                $headers = 'Content-Type: application/json';
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, $headers);
                $res = json_decode(curl_exec($curl), true);
                curl_close($curl);

                    foreach ($res as $post)
                    {

                        $this->sendPost($post);
                        //$this->reply('asd');
                    }

                break;
            case ('За месяц'):
                $keyboard = [
                    'inline_keyboard' =>
                        [
                            [
                                ['text'=> 'Январь', 'callback_data' => 'sumAllSellerByMonth#'.'1|0'],
                                ['text'=> 'Февраль', 'callback_data' => 'sumAllSellerByMonth#'.'2|0'],
                                ['text'=> 'Март', 'callback_data' => 'sumAllSellerByMonth#'.'3|0'],
                            ],
                            [
                                ['text'=> 'Апрель', 'callback_data' => 'sumAllSellerByMonth#'.'4|0'],
                                ['text'=> 'Май', 'callback_data' => 'sumAllSellerByMonth#'.'5|0'],
                                ['text'=> 'Июнь', 'callback_data' => 'sumAllSellerByMonth#'.'6|0'],
                            ],
                            [
                                ['text'=> 'Июль', 'callback_data' => 'sumAllSellerByMonth#'.'7|0'],
                                ['text'=> 'Август', 'callback_data' => 'sumAllSellerByMonth#'.'8|0'],
                                ['text'=> 'Сентябрь', 'callback_data' => 'sumAllSellerByMonth#'.'9|0'],
                            ],
                            [
                                ['text'=> 'Октябрь', 'callback_data' => 'sumAllSellerByMonth#'.'10|0'],
                                ['text'=> 'Ноябрь', 'callback_data' => 'sumAllSellerByMonth#'.'11|0'],
                                ['text'=> 'Декабрь', 'callback_data' => 'sumAllSellerByMonth#'.'12|0'],
                            ],
                        ],
                ];

                $reply_markup = json_encode($keyboard);
                $this->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $this->telegramm_id,
                        'text' => 'Выберите месяц для отчета',
                        'reply_markup'=>$reply_markup,
                    ));
                break;
                 case(preg_match('/^(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}$/', $command, $output_array)? true : false):
                   $this->reply($this->report->sumToDay($this->telegramm_id, $output_array[0]));
                    break;
                /*
                case('Внести'):
                    $arr = new CreateCsv();
                    $arr->saveCsv($this->telegrammid);
                    break;
                case("💵 Продажи за сегодня по артикулу"):
                    self :: today();
                    break;

                case("💰 Продажи за этот год"):
                    self :: callReport($this->messageId);
                    break;
                case("Чистая прибыль"):
                    self :: callProfit();
                    break;
                case('/toall'):
                    parent :: reply('Сообщение для всех от бота', '@myfunnybant');
                    break;
                case("🙋 Приступить к работе"):
                    parent :: reply('Запрос принят');
                    parent :: reply('Поступил запрос на прием - '. $this->userName, MANAGER);
                    parent :: updateStatusUser('newseller', $this->telegrammid);
                    break;
                case ("/add"):
                    //parent :: reply ($word[0] . $word[1]);
                    self :: additems($word[1]);
                    break;

                case('📉 Посмотреть расходы'):

                    self :: callShowExp();

                        break;

                    }
                }*/
        }
    }
    public function sendTelegram($method, $response){
        try{
           $ch = curl_init('https://api.telegram.org/bot' . $this->botAPI . '/' . $method);
        //$ch = curl_init('https://exemple.com/' . $this->botToken . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
        }catch (Exception $e){
            trigger_error("Bot.php SendTelegram: 323" . $e->getMessage(), E_USER_WARNING);
            die();
        }

    }
    public function getKeyBoard($data, $subject='Выберите из категории', $userTelegam_id = null){
        if(is_null($userTelegam_id)){
            $telegram_id = $this->telegramm_id;
        }else{
            $telegram_id = $userTelegam_id;
        }
        $keyboard = [
            "keyboard" => $data,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,

       ];
        $reply_markup = json_encode($keyboard,true);
        $this->sendTelegram(
            'sendMessage',
            array(
                'chat_id' => $telegram_id,
                'text' => $subject,
                'reply_markup'=> $reply_markup,
            ));

        //file_get_contents($this->botToken . "/sendMessage?{$data}&reply_markup={$keyboard}");
    }
    public function sendButtons($telegram_id, $keyboard, $textmessage)
    {


        $reply_markup = json_encode($keyboard);

            $this->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $telegram_id,
                    'text' => $textmessage,
                    'reply_markup'=>$reply_markup,
                ));

    }
    public function delSaleItems($idSaleItems, $chatId)
    {

        $params = [
            'id'=> $idSaleItems,
            'user'=>$chatId
            ];

        $query = 'SELECT * FROM `saleitems` WHERE ind = :id AND sale_to_chatID = :user';
        $stmt = $this->connect->prepare($query);
        $stmt->execute($params);

        if($stmt->rowCount() > 0) {
            while($row = $stmt->fetch(PDO::FETCH_LAZY))
            {
                try{

                    $id = $idSaleItems;
                    $query = "DELETE FROM `saleitems` WHERE `ind` = ?";
                    $params = [$id];
                    $stmt = $this->connect->prepare($query);
                    $stmt->execute($params);
                    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/saleitems/';

                    unlink($uploaddir . basename($row->sale_file));
                }catch(Exception $e){
                    trigger_error("Bot.php SendTelegram: 392 стр" . $e->getMessage(), E_USER_WARNING);
                }


                $this->reply('Запись - ' . $idSaleItems . ' - удалена', $chatId);
            }
        }



        //$db = new \Buki\Pdox($this->config);

        //$db->table('saleitems')->where('ind', $idSaleItems)->delete();
        //unlink($file);

        //return "done";
    }
    public function updateCat($idSaleItems, $category, $chatId)
    {

        $query = "UPDATE `saleitems` SET `category` =:category WHERE `ind` =:ind";
        $params = [
            ':ind' => $idSaleItems,
            ':category' => $category,
        ];

        try {
            $stmt = $this->connect->prepare($query);
            $stmt->execute($params);
            $this->reply('Запись - ' . $idSaleItems . ' - установлена категория: ' . $category , $chatId);

        }catch (PDOException $e){
            trigger_error("Bot.php SendTelegram: 424" .$e->getMessage() . $idSaleItems . '|' . $category, E_USER_WARNING);
            die();
        }

    }

    public function updateDate($idSaleItems, $chatId)
    {
        $date = new \DateTime('- 1 day');
        $query = "UPDATE `saleitems` SET `date_sale` =:date_sale WHERE `id` =:id";
        $params = [
            ':id' => $idSaleItems,
            ':date_sale' => $date->format('Y-m-d'),
        ];

        try {
            $stmt = $this->connect->prepare($query);
            $stmt->execute($params);
            $this->reply('Запись - ' . $idSaleItems . ' - установлена дата: ' . $date->format('Y-m-d') , $chatId);

        }catch (PDOException $e){
            $this->reply('Ошибка изменения даты: ' . $date->format('Y-m-d') .'|'. $idSaleItems . '|' .$idSaleItems, $chatId);
            trigger_error("Bot.php SendTelegram: 425" .$e->getMessage() . $idSaleItems . '|' . $date->format('Y-m-d'), E_USER_WARNING);
            die();
        }

    }

    public function addSaleToAnotherSeller($idSaleItems, $chatId)
    {
        //$this->reply('Запись - ' . $idSaleItems, $chatId);
        $arrUser = [];
        $params = [
            'status'=>'seller',
        ];
        $query = 'SELECT telegram_id, first_name FROM `users`';
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {

            while ($row = $stmt->fetch(PDO::FETCH_LAZY))
            {
                $arrUser[]=[
                    'text'=> $row->first_name, 'callback_data' => 'insertSalesForSeller#'.$row->telegram_id.'|'.$idSaleItems,
                ];

            }
        }

        $keyboard = [
                    'inline_keyboard' =>
                        [
                            $arrUser,
                        ]
                ];

        $reply_markup = json_encode($keyboard);

                    $this->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $chatId,
                            'text' => 'Выберите продавца',
                            'reply_markup'=>$reply_markup,
                        ));

    }

    public function insertSalesForSeller($telegram_id, $idSaleItems, $chatId)
    {
        $query = "UPDATE `saleitems` SET `sale_to_chatID` =:telegram_id WHERE `ind` =:ind";
        $params = [
            ':ind' => $idSaleItems,
            ':telegram_id' => $telegram_id,
        ];

        try {
            $stmt = $this->connect->prepare($query);
            $stmt->execute($params);
            $this->reply('Запись - ' . $idSaleItems . ' - отнесена за : ' . $telegram_id , $chatId);

        }catch (PDOException $e){
            trigger_error("Bot.php SendTelegram: 483" .$e->getMessage() . $idSaleItems , E_USER_WARNING);
            die();
        }
    }
    public function getManagerId()
    {
        return $this->managerId;
    }
    public function today()
    {
        $data = $this->connect->prepare("SELECT * FROM `saleitems` WHERE `sale_to_chatID` = ? AND `date_sale` = ?");
        $data->execute([$this->telegramm_id, ]);
        if($data->rowCount() > 0){
            while ($row = $data->fetch(PDO::FETCH_LAZY)) {
                //$this->telegram_id = $row->telegram_id;
                $this->status = $row->status;
                //$this->first_name = $row->first_name;
                $this->dateAdd = $row->dateadduser;
            }
        }else{
            return 'denied';
        }
    }
     public function getBotToken()
    {
        return $this->botToken;
    }
    public function getTelegrammId()
    {
        return $this->telegramm_id;
    }
    public function getBotAPI()
    {
        return $this->botAPI;
    }
}
