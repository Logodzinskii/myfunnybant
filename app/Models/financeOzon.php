<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financeOzon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'article',
        'month',
        'year',
        'item',
        'sale_price'
    ];
}
