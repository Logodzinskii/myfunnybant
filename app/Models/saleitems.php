<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleitems extends Model
{
    use HasFactory;

    protected $fillable = [
        'ind',
        'sale_to_chatID',
        'date_sale',
        'count_items',
        'sale_price',
        'sale_file',
        'category',
        'id_shop',
        'place',
    ];
}
