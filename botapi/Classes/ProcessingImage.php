<?php

class ProcessingImage extends Exception
{
    public function __construct($image, $capture, $bot)
    {
        $db = DateBase::get_instance();
        $this->dbh = $db->getConnection();

        try {
            $this->bot = $bot;
            $this->userPhoto = $image;
            $this->userPhotoCaption = $capture;

        } catch (PDOException $e) {
            trigger_error("ProcessingImage.php select: " . $e->getMessage(), E_USER_WARNING);
        }
    }

    public function writeAndSaveImageSalesToDb()
    {

        $photo = array_pop($this->userPhoto);
        $res = $this->bot->sendTelegram(
        'getFile',
            [
                'file_id' => $photo['file_id'],
            ]
        );

        $res = json_decode($res, true);


        if ($res['ok'] && $this->userPhotoCaption == '') {

        $src = 'https://api.telegram.org/file/bot' . $this->bot->getBotAPI() . '/' . $res['result']['file_path'];
        $dest = $_SERVER['DOCUMENT_ROOT'].'/botapi/fotoitems/'. basename($src);

            if (copy($src, $dest)) {
            //$this->resize_image($dest,1080,1080,true);
            $this->bot->reply(basename($src) . ' /add@артикул|Имя|Краткое описание|Описание|В наличии?|Запасы|Базова цена|Категории|Метки|имя файла');
            }else{
                $this->bot->reply('error load file');
            }
        }else{

            $src = 'https://api.telegram.org/file/bot' . $this->bot->getBotAPI() . '/' . $res['result']['file_path'];
            $dest = $_SERVER['DOCUMENT_ROOT']. '/public/images/saleitems/' . basename($src);
            $idchat = $this->bot->getTelegrammid();
            $dateadd = date('Y-m-d');

            if (copy($src, $dest)) {
                //self :: resize_image($dest,600,800,true);
                $arrData = explode(',',$this->userPhotoCaption);
                $iditem = explode('.',basename($src));
                $id = explode('_',$iditem[0]);
                $totalPrice = $arrData[0] * $arrData[1];
                if (isset($arrData[2])){
                    $place = $arrData[2];
                }else{
                    $place = 'не указано';
                }
                try {
                    $sth = $this->dbh->prepare("INSERT INTO `saleitems` SET `id` = :id, `sale_to_chatID` = :sale_to_chatID, `date_sale` = :date_sale, `count_items` = :count_items, `sale_price` = :sale_price, `sale_file` = :sale_file, `place` = :place");
                    $sth->execute([
                        'id' => $id[1],
                        'sale_to_chatID' => $idchat,
                        'date_sale'=>$dateadd,
                        'count_items'=>$arrData[0],
                        'sale_price' => $arrData[1],
                        'sale_file' => $dest,
                        'place'=>$place,
                        ]);
                    // Получаем id вставленной записи

                    $keyboard = [
                        'inline_keyboard' => [


                                [
                                    ['text'=> 'Бантики', 'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Бантики'],
                                    ['text'=> 'Заколки', 'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Заколки'],
                                    ['text'=> 'Ободки',  'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Ободки'],
                                ],
                                [
                                    ['text'=> 'Подвески', 'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Подвески'],
                                    ['text'=> 'Значки',   'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Значки'],
                                    ['text'=> 'Прочие',   'callback_data' => 'updateCat#'.$this->dbh->lastInsertId().'|Прочее'],
                                ],
                                [
                                    ['text' => 'Удалить - '. $this->dbh->lastInsertId(), 'callback_data' => 'delSaleitems#items|'.$this->dbh->lastInsertId()],
                                ],
                                [
                                    ['text' => 'Занести продажу: '. $this->dbh->lastInsertId() . ' за вчерашний день -', 'callback_data' => 'updateDate#'.$this->dbh->lastInsertId().'|'.$this->dbh->lastInsertId()],
                                ],

                            ],

                    ];

                   $reply_markup = json_encode($keyboard);

                    $this->bot->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $idchat,
                            'text' => 'Запись - ' . $this->dbh->lastInsertId() . ', внесена',
                            'reply_markup'=>$reply_markup,
                        ));


                }catch (PDOException $e){
                    trigger_error("Bot.php select: delSaleitems " . $e->getMessage(), E_USER_WARNING);
                    die();
                }

            }else{
                $this->bot->reply('error load file');
            }
        }

        exit();
    }

}
