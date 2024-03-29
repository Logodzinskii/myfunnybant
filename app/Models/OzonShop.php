<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonShop extends Model
{
    /**
     * Модель для подсчета количества лайков для товара
     */
    use HasFactory;

    protected $fillable=[
        'ozon_id',
        'url_chpu',
        'like_count',
    ];


}
