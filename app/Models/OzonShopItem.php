<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class OzonShopItem extends Model
{
    use HasFactory;
    use SearchableTrait;

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

    protected $searchable = [
        'columns' => [
            'name'=>10,
            'description' => 5,
            'header'=>3,
        ],
    ];
}
