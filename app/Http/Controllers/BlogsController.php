<?php

namespace App\Http\Controllers;

use App\Models\blogs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class BlogsController extends Controller
{
    //CRUD from blog
    public function index()
    {
        return view('admin.adminblog.blogMaker');
    }
    public function list()
    {
        return view('admin.adminblog.adminBlogList', ['blogs'=>DB::table('blogs')
        ->paginate(20)]);
    }


    public function create(Request $request)
    {
        $blogs = blogs::create([
            'blog_author_name'=>$request->blog_author_name,
            'blog_author_link'=>$request->blog_author_link,
            'blog_header'=>$request->blog_header,
            'blog_desrypion'=>$request->blog_desrypion,
            'blog_content'=>$request->blog,
        ]);

        return view('blog.blog',['data'=>blogs::all()]);
    }

    public function delete(Request $request)
    {
        $blog = blogs::find($request->id);
        $blog->delete();
        
        return view('admin.adminblog.adminBlogList', ['blogs'=>DB::table('blogs')
        ->paginate(20)]);
    }

    public function saveImage(Request $request)
    {
        try{
            $image = request()->file('image');
            $fileName = time() . ' ' . $image->getClientOriginalName();
            $path = $image->storeAs('blogs', $fileName);        

            return response()->json(asset('storage/blogs/'.$fileName),200);
        }catch(Exception $e){
            return $e->getMessage();
        };
        
    }
}