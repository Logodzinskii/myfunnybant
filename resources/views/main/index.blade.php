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
                        <div class=" overflow-hidden eli" style="height: 75vh">
                            <div class="card-body side d-flex justify-content-center overflow-hidden flex-wrap" style="">
                                <div class="border-0">
                                    <div class="position-relative" style=" height: 10vh">
                                        <div class="card-gradient position-absolute top-0" style="width: 100%; height: 100%"></div>
                                        <h5 class="text-center position-absolute top-0" >{{$item->name}}</h5>
                                    </div>

                                    <div class="p-0 m-0" style="height: 50vh; overflow: hidden">
                                        <img src="{{json_decode($item->images, true)[0]['file_name']}}" class="mx-auto d-block img-fluid" alt="{{$item->name}}">
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-around bg-light rounded-2" style="height: 15vh">
                                    <div class="w-100 d-flex justify-content-center">
                                        <div class="d-flex flex-nowrap rounded-2" style="font-size: 2em">
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
                                        </div>
                                        <div class="ps-4 pt-3">
                                            @if(session()->has('ozon_id') && array_search($item->ozon_id, session()->get('ozon_id')) !== false)
                                                <i class="bi-like p-3 like" data-heart="{{$item->ozon_id}}"></i>
                                            @else
                                                <i class="bi p-3 like" data-heart="{{$item->ozon_id}}"></i>
                                            @endif
                                            <span class="badge text-bg-secondary rounded-circle" style="left:25px">{{\App\Models\OzonShop::where('ozon_id', '=', $item->ozon_id)->first()->like_count}}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-around">
                                        <a href="{{route("seller.ozon", ['url'=>$item->name])}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">в ozon.ru</a>
                                        <a href="{{url("shop", ['offer_chpu'=>\App\Models\OzonShop::where('ozon_id', '=', $item->ozon_id)->first()->url_chpu])}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Подробнее</a>
                                        <div class="add-cart my-button btn btn-sm g-2 h-4 m-1 text-white" data-add-cart="{{$item->ozon_id}}">
                                            <i class="bi-basket3"></i>
                                            В корзину
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
    <div class="container mt-5 mb-5 d-flex justify-content-center flex-wrap">

        {{$data[0]->links()}}

    </div>
</section>
@endsection
