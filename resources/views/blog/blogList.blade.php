@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            
            @if(isset($category))
            <li class="breadcrumb-item"><a href="{{url('blogs')}}">Блоги</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$category['url_name']}}</li> 
            @else
            <li class="breadcrumb-item active">Блоги</li>
            @endif            
        </ol>
      </nav>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3 basic-staggering-demo">
        @foreach ($blogs as $blog)
            <div class="mb-2 mt-2">
                <div class="card border-0">
                    <div class="card-body">
                        <a href="\blogs\{{\App\Http\Controllers\StatGetOzon::chpuGenerator($blog->blog_category)}}\{{\App\Http\Controllers\StatGetOzon::chpuGenerator($blog->blog_header)}}" class="link-offset-2 link-underline link-underline-opacity-0">
                            @php
                                preg_match(
                                    '/<img[^>]* src=\"([^\"]*)\"[^>]*>/', 
                                    $blog->blog_content,
                                    $matches,
                                    );
                            @endphp                            
                            
                            @if (empty($matches))
                            <div class="overflow-hidden d-flex justyfi-content-center align-items-center" style="height: 250px; margin:auto">
                                <img src="{{asset('storage/blogs/1706336126 not_foto.jpg')}}" class="figure-img img-fluid rounded" alt=''/> 
                            </div>
                                @else
                                @foreach ($matches as $image)
                                    <div class="overflow-hidden d-flex justyfi-content-center align-items-center" style="height: 250px; margin:auto">
                                        {!! $image !!} 
                                    </div>       
                                        @php
                                           break; 
                                        @endphp
                                @endforeach
                            @endif                            
                            <h3 class="card-title">{{\Illuminate\Support\Str::limit($blog->blog_header, 50, $end='...')}}</h3>
                            <p class="card-text">{{\Illuminate\Support\Str::limit($blog->blog_desrypion, 100, $end='...')}}</p>
                        </a>
                        <div class="d-flex flex-column fs-6 text-secondary">
                            <span>Автор: {{$blog->blog_author_name}}</span>
                            <span><link rel="shortcut icon" href="{{asset('storage/blogs/1706336126 not_foto.jpg')}}" type="image/x-jpg">{{$blog->blog_author_link}}</span>
                        </div>
                    </div>
                </div>
            </div>  
        @endforeach
    </div>
    <div class="container mt-5 mb-5 d-flex justify-content-center flex-wrap">

        {{$blogs->links()}}

    </div>
</div>
@endsection