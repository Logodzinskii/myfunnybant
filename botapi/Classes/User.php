<?php

class User extends Exception
{
    private $telegram_id, $first_name, $status, $dateAdd;
    public $db;
    public function __construct($userObject)
    {
        $this->telegram_id = $userObject['message']['chat']['id'];
        $this->first_name = $userObject['message']['chat']['first_name'];
        $this->status = '';
        $this->dateAdd = '2022-02-05';
        $db = DateBase::get_instance();
        $this->db = $db->getConnection();

    }

    public function verifyUser()
    {

        try{

            $data = $this->db->prepare("SELECT * FROM `users` WHERE `telegram_id` = ?");
            $data->execute([$this->telegram_id]);
            if($data->rowCount() > 0){
                while ($row = $data->fetch(PDO::FETCH_LAZY)) {
                    //$this->telegram_id = $row->telegram_id;
                    $this->status = $row->status;
                    //$this->first_name = $row->first_name;
                    $this->dateAdd = $row->dateadduser;
                    file_put_contents('user.txt', $row->status, FILE_APPEND | LOCK_EX);
                }
            }else{
                return 'denied';
            }

        }catch (PDOException $e){
            trigger_error("User.php select: " . $e->getMessage(), E_USER_WARNING);
            die();
        }
    }

    public function updateStatusUser($newStatus, $idSallers=null){

        try{

            $query = "UPDATE `users` SET status = :status WHERE telegram_id = :telegram_id";
            $params = [
                'telegram_id' => $idSallers,
                'status' => $newStatus
            ];
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $newStatus;
        }catch (PDOException $e){
            trigger_error("Shop.php select: " . $e->getMessage(), E_USER_WARNING);
            die();
        }

    }

    public function getTelegramId()
    {
        return $this->telegram_id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDateAdd()
    {
        return $this->dateAdd;
    }

}