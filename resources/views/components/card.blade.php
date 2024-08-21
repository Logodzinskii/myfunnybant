<div class="col " id="{{$key}}">
    <div class=" overflow-hidden eli">
        <div class="card-body side d-flex justify-content-center overflow-hidden flex-wrap" style="width: 100%">
            <div class="border-0 col-12">
                <div class="position-relative" style="min-height: 100px">
                    <div class="card-gradient position-absolute top-0" style="width: 100%; height: 100%"></div>
                    <h5 class="text-center position-absolute top-0" >{{htmlspecialchars_decode($header, ENT_QUOTES)}}</h5>
                </div>
                <div class="p-0 m-0" style="overflow: hidden; height:35vh;">
                    <img src="{{$img}}" class="mx-auto" style="object-fit: cover; width: 100%;" alt="{{htmlspecialchars_decode($header, ENT_QUOTES)}}">
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-around bg-light rounded-2" >
                <div class="w-100 d-flex justify-content-center">
                    <div class="d-flex flex-nowrap rounded-2" style="font-size: 2em">
                        <div class="price p-1  border-secondary">
                            <s>{{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $ozonid)->first()->price}} &#8381;</s>
                        </div>
                        <div class="action-price p-1  border-secondary">
                            {{\App\Models\StatusPriceShopItems::where('ozon_id', '=', $ozonid)->first()->action_price}} &#8381;
                        </div>
                        <form method="post" action="{{route('add.cart')}}">
                            @csrf
                            <input type="hidden" name="ozon_id" value="{{$ozonid}}" />
                        </form>
                    </div>
                    <div class="ps-4 pt-3">
                        @if(session()->has('ozon_id') && array_search($ozonid, session()->get('ozon_id')) !== false)
                            <i class="bi-like p-3 like" data-heart="{{$ozonid}}"></i>
                        @else
                            <i class="bi p-3 like" data-heart="{{$ozonid}}"></i>
                        @endif
                        <span class="badge text-bg-secondary rounded-circle" style="left:25px">{{\App\Models\OzonShop::where('ozon_id', '=', $ozonid)->first()->like_count}}</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-around">
                    <a href="{{route("seller.ozon", ['url'=>$header])}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">в ozon.ru</a>
                    <a href="{{url("shop", ['offer_chpu'=>\App\Models\OzonShop::where('ozon_id', '=', $ozonid)->first()->url_chpu])}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Подробнее</a>
                    <div class="add-cart my-button btn btn-sm g-2 h-4 m-1 text-white" data-add-cart="{{$ozonid}}">
                        <i class="bi-basket3"></i>
                        В корзину
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
