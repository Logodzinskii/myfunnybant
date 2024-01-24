@extends('layouts.app')
@section('content')
<div class="container">
    @foreach ($data as $blog)
        {!! $blog->blog_content !!}
    @endforeach
</div>
@endsection