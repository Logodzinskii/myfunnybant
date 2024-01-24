<?php

namespace App\Http\Controllers;

use App\Models\blogs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogsController extends Controller
{
    //CRUD from blog
    public function index()
    {
        return view('admin.adminblog.blogMaker');
    }

    public function create(Request $request)
    {
        $blogs = blogs::create(['blog_content'=>$request->blog]);
        return view('blog.blog');
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