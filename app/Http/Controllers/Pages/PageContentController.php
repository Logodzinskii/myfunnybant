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

    public function categoryView($category)
    {
        $categores = blogs::select('blog_category')->groupBy('blog_category')->get();
        $cat = '';
        foreach($categores as $categoryBd)
        {
            StatGetOzon::chpuGenerator($categoryBd->blog_category) == $category ? $cat .= $categoryBd->blog_category : 'null';
        }
        
        return view('blog.blogList', ['blogs'=>DB::table('blogs')
                                                ->where('blog_category','=', $cat)
                                                ->paginate(20),
                                            'category'=>['url'=>$category,
                                                        'url_name'=>$cat]]);
    }

    public function blog($category, $chpu)
    {
        $blogs = blogs::all();
        $id = '';
        foreach($blogs as $blog){
            StatGetOzon::chpuGenerator($blog->blog_header) === $chpu ? $id = $blog->id : '';
        }
        $cat = blogs::select('blog_category')
        ->where('id', $id)
        ->get();
        
        $category = StatGetOzon::chpuGenerator($cat[0]->blog_category);
        return view('blog.blog', ['data'=>blogs::where('id', '=', $id)
                                                            ->get(),
                                                            'category'=>['url'=>$category,
                                                            'url_name'=>$cat[0]->blog_category]]);
    }
}