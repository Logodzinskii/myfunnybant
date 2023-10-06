<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
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
    ];
}
