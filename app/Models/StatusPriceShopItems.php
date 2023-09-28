<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPriceShopItems extends Model
{
    use HasFactory;

    protected $fillable=[
        'ozon_id',
        'status',
        'price',
        'action_price',
        'fbs',
        'fbo',
    ];
}
