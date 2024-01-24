<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blogs extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'blog_author_name',
        'blog_author_link',
        'blog_header',
        'blog_desrypion',
        'blog_content',
    ];
}
