@extends('layouts.app')
@section('content')
    <style type="">

    </style>
<section class="col-lg-12">
    @foreach($data as $key=>$items)
        <div class="container">
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">
                @foreach($items as $item)
                    <div class="col" id="{{$key}}">
                        <div class="card overflow-hidden" >
                            <div class="card-body side d-flex position-relative justify-content-center overflow-hidden" style="height: 50vh">
                                <div class="border-0 position-absolute top-0" style="min-height: 100px">
                                    <div class="" style="width: 100%; min-height: 60px; background-color: rgba(244, 232, 250, 0.7)">
                                        <h5 class="text-center" >{{$item->name}}</h5>
                                    </div>

                                </div>
                                @for($i=0; $i<=0; $i++)
                                    <div class="p-0 m-0 ">
                                        <img src="{{$item['images'][$i]['file_name']}}" alt="{{$item['name']}}">
                                    </div>
                                @endfor
                                    <div class="position-absolute bottom-0 end-0 d-flex flex-wrap">
                                        <form id="itemInfo" method="post" action="{{route('seller.show')}}">
                                            @csrf
                                            <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id" value="{{$item['attributes']['id']}}">
                                            <button type="submit" class="btn btn-sm btn-primary bg-primary g-2 h-4 m-1">Подробнее</button>
                                        </form>
                                        <!--<a href="{{route("seller.show", ['id'=>$item])}}" class="btn btn-sm btn-outline-dark">Подробнее</a>-->

                                        <a href="{{route("seller.ozon", ['url'=>$item['name']])}}" class="btn btn-sm btn-primary bg-primary g-2 h-4 m-1">ozon.ru</a>
                                        <div class="btn btn-sm btn-primary bg-primary g-2 h-4 m-1">
                                            <span>
                                                @foreach($item->attributes['colors'] as $attribute)
                                                    {{'#'.$attribute}}
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</section>
@endsection
