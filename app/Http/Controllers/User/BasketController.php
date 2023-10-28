<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class BasketController extends Controller
{
    public $userId, $basket;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->basket = \Cart::session($userId);

    }

    public function getCountItem()
    {

        try{

            $total = $this->basket->getSubTotal();
            $totalQuantity = $this->basket->getTotalQuantity();

            return [$total,$totalQuantity];
        }catch (Exception $exception)
        {
            return $exception->getMessage();
        }

    }
}
