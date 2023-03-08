@extends('admin.layouts.adminHome')
@section('content')
    <div class="col-12 d-flex justify-content-start flex-wrap">
        @foreach($items as $item)
        <div class="card col-6 col-sm-3 col-lg-4">
                <div class="card-header">
                <p>{{$item['date_sale']}}</p>
                </div>
                <div class="card-body">
                    @php
                        $re = '/(file_[0-9]{0,10}.jpg)/';

                        preg_match_all($re, $item['sale_file'], $matches, PREG_SET_ORDER, 0);

                    @endphp
                    <img src="{{asset('/images/saleitems/'.$matches[0][0])}}" class="img-thumbnail" />
                </div>
                <div class="card-footer d-flex justify-content-start flex-wrap">
                    <p>Количество: {{$item['count_items']}};</p>
                    <p>Цена: {{$item['sale_price']}};</p>
                    <p>Итого: {{$item['count_items'] * $item['sale_price']}};</p>
                </div>
        </div>
        @endforeach
    </div>
    {{$items->links()}}
@endsection