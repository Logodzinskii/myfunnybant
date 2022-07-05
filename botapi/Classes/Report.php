<?php

class Report extends Exception
{
    public function __construct()
    {
        $db = DateBase::get_instance();
        $this->dbh = $db->getConnection();
    }

    public function toDay($telegramm_id,$date=null)
    {


           if(is_null($date)){
            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("Y-m-d");
        }else{

            $today = $date;
        }

            $str = '';
            $params = [
            'telegram_id' => $telegramm_id,
            'date'=> $today,
        ];
        $query = 'SELECT sale.id, date_sale, count_items, sale_price, usr.first_name, (count_items * sale_price) as total FROM `saleitems` as sale INNER JOIN users usr on sale.sale_to_chatID = usr.telegram_id WHERE sale.sale_to_chatID=:telegram_id AND sale.date_sale = :date';
            //$query = 'SELECT id, date_sale, count_items, sale_price, (count_items * sale_price) as total FROM `saleitems` WHERE sale_to_chatID = :telegram_id AND date_sale = :date';
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
            if($stmt->rowCount() > 0){
                while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                    //echo $row->id . ' - ' .$row->total . PHP_EOL;
                    $str = $str. $row->first_name . ': ' . $row->date_sale . ' Арт.- ' . $row->id . ' Количество- ' .$row->count_items. ', 💵 За шт.- ' . $row->sale_price .', 💰 - '. $row->total ."\n" ;
                }
                $unswer = $str;
            }else{
                $unswer = 'Ещё ничего не продано';
            }
        return $unswer;
    }
    public function enotherDay()
        {
            $res=[];
            $query = 'select date_sale as date, sum(sale_price*saleitems.count_items) as sumSale from saleitems where YEAR(date_sale) = YEAR(curdate()) group by date order by date DESC LIMIT 10';
            $stmt = $this->dbh->prepare($query);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
               while ($row = $stmt->fetch(PDO::FETCH_LAZY))
               {
                   $res[] = [

                           ['text'=> date("d.m.Y", strtotime($row->date)) . '-' . $row->sumSale , 'callback_data' => 'showReportAnonotherDay#'.$row->date.'|0'],
                    ];
               }
            }

            return json_encode($res);
        }


    public function sumToDay($telegramm_id)
    {
            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("Y-m-d");
            $str = '';
            $params = [
                'telegram_id' => $telegramm_id,
                'date'=> $today,
            ];

            $query = 'SELECT count_items, sale_price, usr.first_name, SUM(count_items * sale_price) as total FROM `saleitems` as sale INNER JOIN users usr on sale.sale_to_chatID = usr.telegram_id WHERE sale.sale_to_chatID=:telegram_id AND sale.date_sale = :date';

            $stmt = $this->dbh->prepare($query);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_LAZY);

            if(!is_null($row->total)){

                $unswer = $today . ' ' . $row->first_name . ': ' . ' Продано всего на: ' . $row->total;

            }else{

                $unswer = 'Ещё ничего не продано';

            }

        return $unswer;
    }
    public function sumAllSeller($telegramm_id, $date=null)
    {
        if(is_null($date)){
            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("Y-m-d");
        }else{

            $today = $date;
        }


            $str = '';
            $params = [
            'date'=> $today,
        ];
            $query = 'SELECT id, date_sale, sale_to_chatID, count_items, sale_price, (count_items * sale_price) as total FROM `saleitems` WHERE date_sale = :date';
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
            if($stmt->rowCount() > 0){
                while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                    //echo $row->id . ' - ' .$row->total . PHP_EOL;
                    $str = $str.  $row->sale_to_chatID . ' Арт.- ' . $row->id . ' Количество- ' .$row->count_items. ', 💵 За шт.- ' . $row->sale_price .', 💰 - '. $row->total ."\n" ;
                }
                $unswer = $str;
            }else{
                $unswer = 'Ещё ничего не продано';
            }
        return $unswer;
    }
    public function sumAllSellerByMonth($date=null)
    {
        if(is_null($date)){
            $date = new DateTime('NOW');
            $y = $date->format("Y");
            $m = $date->format("m");
            $d = $date->format("d");
            $today = $date->format("M");
        }else{
            $today = $date;
        }


        $str = '';
        $params = [
            'date'=> $today,
        ];
        $query = 'SELECT MONTH(date_sale) as month_sale, (count_items * sale_price) as total FROM `saleitems` WHERE MONTH(date_sale) = :date';
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($params);
        if($stmt->rowCount() > 0){
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                //echo $row->id . ' - ' .$row->total . PHP_EOL;
                $str = $str.  $row->month_sale .', 💰 - '. $row->total ."\n" ;
            }
            $unswer = $str;
        }else{
            $unswer = 'Ещё ничего не продано';
        }
        return $unswer;
    }
}
