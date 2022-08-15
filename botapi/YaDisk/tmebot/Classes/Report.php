<?php

class Report
{
    protected $connection;
    public function __construct()
    {
        $connection = DateBase::get_instance();
        $this->connection = $connection->getConnection();

    }

    public function addExpenses($arrExpenses)
    {
        // https://github.com/hflabs/dadata-php
        require_once $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/tmebot/Classes/Dadata.php';

        $token = "b280cf1246433ce7eac426b191f1f8fe65f7eab0";
        $dadata = new Dadata($token, null);
        $dadata->init();

        $str = $arrExpenses['location'];

        $array = get_object_vars(json_decode($str));

        if($array['latitude'] == 0 && $array['longitude'] == 0)
        {

            $result = $dadata->geolocate(56.819124, 60.631784);

        }else{

            $result = $dadata->geolocate($array['latitude'], $array['longitude']);

        }


        $date = new DateTime('NOW');
        $today = $date->format("Y-m-d");
        try{
            $stmt = $this->connection->prepare("INSERT INTO `expenses` SET `saller` =:saller, `name_expens` =:name_expens, `totalPrice`=:totalPrice, `date`=:date, `location`=:location, `location_name`=:location_name ");
            $stmt->execute([
                'saller'=>'1454009127',
                'name_expens'=>$arrExpenses['name_expens'],
                'totalPrice'=>$arrExpenses['totalPrice'],
                'date'=>$arrExpenses['date'],
                'location'=>$arrExpenses['location'],
                'location_name'=>$result['suggestions'][0]['value']
            ]);

            return $this->connection->lastInsertId();
        }catch (PDOException $e){
            file_put_contents('error.txt', $e->getMessage());
        }

    }
    public function deleteExpenses($id)
    {
        $query = "DELETE FROM `expenses` WHERE `id` = ?";
        $params = [$id];
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return 'Удалено ' . $id;
    }


    public function showExpenses()
    {
        $date = new DateTime('NOW');
        $today = $date->format("m");
        $year = $date->format("Y");
        $res = '';
        $params = [
            'date' => $today,
            'year'=> $year,
        ];
        $query = "SELECT * FROM `expenses` WHERE MONTH(date) =:date AND YEAR(date) =:year ORDER BY date ASC";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        if($stmt->rowCount() > 0)
        {
            while ($row = $stmt->fetch(PDO::FETCH_LAZY))
            {
                $geolocation = json_decode($row->location, true);
                $longtitude = $geolocation['longitude'];
                $latitude = $geolocation['latitude'];
                $res = $res. '📍' .$row->date .', Тип -' . $row->name_expens. ', Расходы -' . $row->totalPrice . ', Место - ' . $row->location_name . PHP_EOL . '<a href="https://yandex.ru/maps/?text='.$latitude.'%2C'.$longtitude.'&z=16.72"> На карте </a>'. PHP_EOL;
            }
            $unswer = $res;
        }else{
            $unswer = 'Ещё ничего не занесено' . $today;
        }
        return $unswer;

    }

}
