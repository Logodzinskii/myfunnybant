<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

use App\Http\Controllers\StatGetOzon;
use App\Models\blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PageContentController extends Controller
{
    public function index()
    {

        return view('blog.blogList', ['blogs'=>DB::table('blogs')
                                                ->paginate(20)]);
    }

    public function blog($chpu)
    {
        $blogs = blogs::all();
        $id = '';
        foreach($blogs as $blog){
            StatGetOzon::chpuGenerator($blog->blog_header) === $chpu ? $id = $blog->id : '';
        }
        
        return view('blog.blog', ['data'=>blogs::where('id', '=', $id)
                                                            ->get()]);

    }
}