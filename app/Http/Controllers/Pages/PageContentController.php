<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\blogs;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    public function index()
    {
        return view('blog.blog', ['data'=>blogs::all()]);
    }
}