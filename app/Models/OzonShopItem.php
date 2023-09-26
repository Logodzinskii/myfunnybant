<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonShopItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'ozon_id',
        'name',
        'images',
        'category',
        'type',
        'header',
        'description',
        'colors',
        'width',
        'height',
        'depth',
        'material',
    ];
}
