@extends('layouts.app')
@section('content')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            $(".eli").on('mouseover',function (){
                let el = $(this);
                let animation = anime({
                    targets: [this],
                    translateY: 5,
                    delay: anime.stagger(70) // increase delay by 100ms for each elements.
                });
                var animateBackground = anime({
                    targets: [this],
                    backgroundColor: 'grey'
                });
            })
            $(".eli").on('mouseout',function (){
                let el = $(this);
                let animation = anime({
                    targets: [this],
                    translateY: 0,
                    delay: anime.stagger(100) // increase delay by 100ms for each elements.
                });
                var animateBackground = anime({
                    targets: [this],
                    backgroundColor: '#fff'
                });
            })
            const height = $(window).height();
            anime({
                targets: '.down',
                translateY: height-50,
                delay: anime.stagger(1000, {start: 3000}) // increase delay by 100ms for each elements.
            });
            anime({
                targets: '.down',
                delay: 6000,
                backgroundColor: 'grey',
                color: '#fff',
                border: '#fff',
            });

        })
    </script>
<section class="col-lg-12 ">
    <div class="position-fixed btn btn-outline-primary top-0 down" style="z-index: 999; "><i class="bi-send"></i></div>
    <div class="position-fixed btn btn-outline-primary down" style="z-index: 999; top: -50px;"><i class="bi-envelope"></i></div>
    @foreach($data as $key=>$items)
        <div class="container  ">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3 basic-staggering-demo">
                @foreach($items as $item)
                    <div class="col " id="{{$key}}">
                        <div class="card overflow-hidden eli" >
                            <div class="card-body side d-flex justify-content-center overflow-hidden flex-wrap" style="min-height: 50vh">
                                <div class="border-0 position-relative" style="min-height: 100px; width: 100%">
                                    <div class="position-absolute top-0" style="width: 100%; min-height: 60px; background-color: rgba(244, 232, 250, 0.7)">
                                        <h5 class="text-center mainh" >{{$item->name}}</h5>
                                    </div>
                                    <div class="p-0 m-0 " style="min-height: 500px">
                                        <img src="{{json_decode($item->images, true)[0]['file_name']}}" alt="{{$item->name}}">
                                    </div>
                                    <div class="position-absolute bottom-0 end-0">
                                        @if(session()->has('ozon_id') && array_search($item->ozon_id, session()->get('ozon_id')) !== false)
                                            <i class="bi-like p-3 like" data-heart="{{$item->ozon_id}}"></i>
                                        @else
                                            <i class="bi p-3 like" data-heart="{{$item->ozon_id}}"></i>
                                        @endif
                                        <span class="badge text-bg-secondary position-absolute top-0 rounded-circle start-0">{{\App\Models\OzonShop::where('ozon_id', '=', $item->ozon_id)->first()->like_count}}</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-around">
                                    <div class="d-flex flex-wrap justify-content-around">
                                        <a href="{{route("seller.ozon", ['url'=>$item->name])}}"><x-main-button text="купить на ozon.ru"></x-main-button></a>
                                        <a href="{{url("shop", ['offer_chpu'=>\App\Models\OzonShop::where('ozon_id', '=', $item->ozon_id)->first()->url_chpu])}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Подробнее</a>
                                    </div>
                                    <div>
                                        <div class="shadow bg-light d-flex flex-nowrap rounded-2" style="font-size: 2em">
                                            <div class="price p-1  border-secondary">
                                                <s>{{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $item->ozon_id)->first()->price}} &#8381;</s>
                                            </div>
                                            <div class="action-price p-1  border-secondary">
                                                {{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $item->ozon_id)->first()->action_price}} &#8381;
                                            </div>
                                            <form method="post" action="{{route('add.cart')}}">
                                                @csrf
                                                <input type="hidden" name="ozon_id" value="{{$item->ozon_id}}" />
                                            </form>
                                            <div class="add-cart my-button btn btn-sm g-2 h-4 m-1 text-white" data-add-cart="{{$item->ozon_id}}"><i class="bi-basket3"></i></div>
                                        </div>
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
