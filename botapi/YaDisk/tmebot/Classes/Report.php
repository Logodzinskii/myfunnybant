<?php

class Report
{
    protected $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function addExpenses($arrExpenses)
    {
        $date = new DateTime('NOW');
        $today = $date->format("Y-m-d");
        try{
            $stmt = $this->connection->prepare("INSERT INTO `expenses` SET `saller` =:saller, `name_expens` =:name_expens, `totalPrice`=:totalPrice, `date`=:date ");
            $stmt->execute([
                'saller'=>$arrExpenses['saller'],
                'name_expens'=>$arrExpenses['name_expens'],
                'totalPrice'=>$arrExpenses['totalPrice'],
                'date'=>$today,
            ]);

            return $this->connection->lastInsertId();
        }catch (PDOException $e){
            file_put_contents('error.txt', $e->getMessage());
        }


    }
}
