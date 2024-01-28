@extends('layouts.app')
@section('content')
<div class="container">
    @foreach ($data as $blog)
    <div class="card border-0">
        <div class="card-body">
            <img src="{!! $data[0]['post_photo'] !!}" alt=''/>
            {!! $blog['post_text'] !!}
        </div>
        <div class="card-footer">
            <div class="d-flex flex-column fs-6 text-secondary">
                <span>Автор: </span>
                
            </div>
        </div>
    </div>  
    @endforeach
    
</div>
@endsection