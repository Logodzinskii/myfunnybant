<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    /**
     * Модель для внесения информации об оформлении заказа пользователем с сайта
     * на странице корзины, пользователь выбирает СДЭК и нажимает оформить заказ
     * информация о заказе попадает в таблицу UserCart
     */
    use HasFactory;
    protected $fillable=[
        'ozon_id',
        'user_id',
        'quantity',
        'price',
        'total_price',
        'cdek_id',
        'cdek_info',
        'delivery_price',
        'offer_id',
        'truck_number',
        'truck_status',
        'status_offer',
    ];
}
