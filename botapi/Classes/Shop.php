<?php

class Shop extends Exception
{
    private $managerId, $shopStatus, $dbh;

    public function __construct()
    {
        $db = DateBase::get_instance();
        $this->dbh = $db->getConnection();

    }

    public function initializeShop($nameBot)
    {
        try{
        //узнаем ид менеджера магазина
        $data = $this->dbh->prepare("SELECT * FROM `telegrambot` WHERE `name` = ?");
        $data->execute([$nameBot]);
           while ($row = $data->fetch(PDO::FETCH_LAZY)) {
               $this->managerId = $row->telegram_id;
               // по ид менеджера проверим статус магазина
               $data = $this->dbh->prepare("SELECT * FROM `users` WHERE `telegram_id` = ?");
               $data->execute([$this->managerId]);
               while ($row = $data->fetch(PDO::FETCH_LAZY)) {
                   $this->shopStatus = $row->open_shop;
               }
           }
        }catch (PDOException $e){
            trigger_error("Shop.php select: " . $e->getMessage(), E_USER_WARNING);
            die();
        }

    }

    public function openCloseShop($status)
    {
        try{
        $query = "UPDATE `users` SET open_shop = :status WHERE telegram_id = :telegram_id";
        $params = [
            'telegram_id' => $this->managerId,
            'status' => $status
        ];
            $stmt = $this->dbh->prepare($query);
            $stmt->execute($params);
        }catch (PDOException $e){
            trigger_error("Shop.php select: " . $e->getMessage(), E_USER_WARNING);
        }
        $status == '1' ? $res = 'открыт' : $res = 'закрыт';
        return 'Магазин ' . $res;
    }

    public function showNewSeller(){
        $sth = $this->dbh->prepare("SELECT * FROM `users` WHERE `status`= ?");
        $sth->execute(['newseller']);
        $array = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getManagerId()
    {
        return $this->managerId;
    }

    public function getShopStatus()
    {
        return $this->shopStatus;
    }
}