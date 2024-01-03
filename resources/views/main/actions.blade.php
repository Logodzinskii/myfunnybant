@extends('layouts.app')
@section('content')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            $('.like').on('click',function(){
                var id = $(this).data('heart');
                var span = $(this);
                $.post('/addlike', {"_token":$('meta[name="csrf-token"]').attr('content'), id: id}, function(data){

                    span.parent().find('span').text(data);
                    span.removeClass('bi');
                    span.removeClass('like');
                    span.addClass('bi-like');
                    let s = "<?php if(session()->has('ozon_id')){echo count(session()->get('ozon_id'));}else{echo 0;} ?>";
                    $('#countLike').text(s);
                });
            })
        })
    </script>
<section class="col-lg-12">
@foreach($data as $action)
        <div class="m-3 p-3 h1 border-bottom border-1 border-danger" style="color: #6610f2">
            <i class="bi-basket3-fill" style="color: mediumvioletred"></i>
            {{$action['actionTitle']}}
        </div>
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">
                    @foreach($action['product'] as $items)
                        @foreach($items as $key=>$item)
                            <x-card header="{{$item->name}}" img="{{json_decode($item->images, true)[0]['file_name']}}" key="{{$key}}" ozonid="{{$item->ozon_id}}"></x-card>
                        @endforeach
                    @endforeach
                </div>
            </div>
    @endforeach
</section>
@endsection
