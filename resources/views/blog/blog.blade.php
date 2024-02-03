@extends('layouts.app')
@section('content')
<div class="container">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url('blogs')}}">Блоги</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{$data[0]->blog_header}}</li>
        </ol>
      </nav>
    @foreach ($data as $blog)
    <div class="card border-0">
        <div class="card-body">
            {!! $blog->blog_content !!}
        </div>
        <div class="card-footer">
            <div class="d-flex flex-column fs-6 text-secondary">
                <span>Автор: {{$blog->blog_author_name}}</span>
                <span><link rel="shortcut icon" href="{{asset('storage/blogs/1706336126 not_foto.jpg')}}" type="image/x-jpg">{{$blog->blog_author_link}}</span>
            </div>
        </div>
    </div>  

    @endforeach
    <!-- Yandex.RTB R-A-2544919-2 -->
    <div id="yandex_rtb_R-A-2544919-2"></div>
    <script>
    window.yaContextCb.push(()=>{
        Ya.Context.AdvManager.render({
            "blockId": "R-A-2544919-2",
            "renderTo": "yandex_rtb_R-A-2544919-2",
            "type": "feed"
        })
    })
    </script>
</div>
@endsection