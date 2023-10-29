 @extends('layouts.app')
 @section('content')
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                    <div class="col card">
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
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h1>{{ $res->name }}</h1>
                            </div>
                            <div>
                                <ul class="d-flex flex-wrap justify-content-between p-0 w-0">

                                    <li class="card p-3 text-center">
                                        <span class="badge bg-secondary">Описание: </span>
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
                            <div class="shadow bg-light d-flex flex-nowrap rounded-2">
                                <div class="price p-1 border-secondary"><s>{{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $res['ozon_id'])->first()->price}} &#8381;</s> </div>
                                <div class="action-price p-1 h-2 border-secondary"> {{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $res['ozon_id'])->first()->action_price}} &#8381;</div>
                                <div class="add-cart my-button btn btn-sm g-2 h-4 m-1 text-white" data-add-cart="{{$res['ozon_id']}}"><i class="bi-basket3"></i></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
            </div>
 @endsection
