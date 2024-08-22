 @extends('layouts.app')
 @section('content')
 <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                    <div class="col ">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach(json_decode($res->images,true) as $i=>$image)
                                    @if($i == 0)
                                <div class="carousel-item active">
                                    @else
                                        <div class="carousel-item ">
                                    @endif
                                    <img src="{{$image['file_name']}}" class="d-block w-100 rounded-1" alt="...">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Предыдущий</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Следующий</span>
                            </button>
                        </div>
                    </div>
                    <div class="col">
                    <div class="p-3">
                        <div class="card-body text-center">
                            <div class="position-relative card-gradient-header">                    
                                <div class="card-gradient position-absolute top-0 shadow" >
                                    <div class="blur"></div>
                                    <h1 class="h3 text-center text-white position-absolute top-0" style="min-height: 250px" >
                                        {{ $res->name }}
                                    </h1>
                                </div>                    
                               
                            </div>
                            <div>
                                <ul class="d-flex flex-wrap justify-content-between p-0 w-0">
                                    <li class="card p-3 text-center">
                                        {{ strip_tags($res->description) }}
                                    </li>
                                    <li class="card p-3 text-center">
                                        <span class="badge bg-secondary">Ширина: </span>
                                        {{ $res->width }} mm
                                    </li>
                                    <li class="card p-3 text-center">
                                        <span class="badge bg-secondary">Высота: </span>
                                        {{ $res->height }} mm
                                    </li>
                                    <li class="card p-3 text-center">
                                        <span class="badge bg-secondary">Глубина: </span>
                                        {{ $res->depth }} mm
                                    </li>
                                    <li class="card p-3 text-center"><span class="badge bg-secondary">Цвета: </span>
                                        @foreach(json_decode($res->colors, true) as $color)
                                        {{$color}}
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                            <div class="shadow bg-light d-flex justify-content-center flex-wrap rounded-2 display-2">
                                <div class="price p-1 border-secondary"><s>{{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $res['ozon_id'])->first()->price}} &#8381;</s> </div>
                                <div class="action-price p-1  border-secondary"> {{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $res['ozon_id'])->first()->action_price}} &#8381;</div>
                                <div class="add-cart my-button btn g-2 px-5 m-1 text-white" data-add-cart="{{$res['ozon_id']}}"><i class="bi-basket" style="font-size: 48px"></i></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
            </div>
 </div>
 @endsection