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
                        @foreach($items as $item)
                        <div class="col" >
                            <div class="card overflow-hidden" >
                                <div class="card-body side d-flex position-relative justify-content-center overflow-hidden" style="height: 50vh">
                                    <div class="border-0 position-absolute top-0" style="min-height: 100px">
                                        <div class="" style="width: 100%; min-height: 60px; background-color: rgba(244, 232, 250, 0.7)">
                                            <h5 class="text-center" >{{$item->name}}</h5>
                                        </div>
                                    </div>

                                        <div class="p-0 m-0 ">
                                            <img src="{{json_decode($item->images,true)[0]['file_name']}}" alt="{{$item['name']}}">
                                        </div>

                                    <div class="position-absolute bottom-0 end-0 d-flex flex-wrap">
                                        <a href="{{route("seller.ozon", ['url'=>$item['name']])}}"><x-main-button text="ozon.ru"></x-main-button></a>
                                        <a href="{{isset(\App\Models\OzonShop::where('ozon_id', '=', $item['id'])->first()->url_chpu)? url("shop", ['offer_chpu'=>\App\Models\OzonShop::where('ozon_id', '=', $item['id'])->first()->url_chpu]): ''}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Подробнее</a>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-center h2">
                                    <div class="price p-2 m-1 border-secondary"><s>{{isset($item->price[0])?$item->price[0]:null}} &#8381;</s> </div>
                                    <div class="action-price p-2 m-1 border-secondary"> {{isset($item->price[1])?$item->price[1]:null}} &#8381;</div>
                                    <div class="">
                                        @if(session()->has('ozon_id') && array_search($item['id'], session()->get('ozon_id')) !== false)
                                            <i class="bi-like p-3 like" data-heart="{{$item['id']}}"></i>
                                        @else
                                            <i class="bi p-3 like" data-heart="{{$item['id']}}"></i>
                                        @endif
                                        <span class="badge text-bg-secondary rounded-circle">{{$item['like']}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
    @endforeach
</section>
@endsection
