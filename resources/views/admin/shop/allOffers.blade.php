@extends('admin.layouts.adminHome')
@section('content')
    <div class="container">

        @foreach($carts as $cart)
            <div class="card ">
                <ul class="card-header list-unstyled">
                    <li class="h4" style="color: #6610f2"><i class="bi-basket3"></i>Номер заказа: {{$cart->id}}</li>
                    <li class="h4">Имя заказчика: {{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->name}}</li>
                    <li class="h4">Email: <a href="mailto:'{{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->email}}'">{{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->email}}</a></li>
                    <li class="h4">Tel: </li>
                    <li class="h4">Статус заказа: <b style="color: mediumvioletred">Новый</b> <i style="color: #6610f2" class="bi-caret-down-square"></i> </li>
                </ul>
                <div class="m-1 container row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
            @foreach(\App\Models\UserCart::where('offer_id','=', $cart->id)->get() as $offer)
                        <div class="shadow row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                            <div class="col-4 p-3">
                                <img src="{{json_decode(\App\Models\OzonShopItem::where('ozon_id','=',$offer->ozon_id)->firstOrFail()->images, true)[0]['file_name']}}" />
                            </div>
                            <ul class="col-6 p-3 list-unstyled">
                                <li class="h5">Номер заказа: {{$offer->id}}</li>
                                <li>User ID: {{$offer->user_id}}</li>
                                <li>Озон ID: {{$offer->ozon_id}}</li>
                                <li class="h5">Количество: <b style="color: #6610f2">{{$offer->quantity}}</b></li>
                                <li >Цена за единицу: {{$offer->price}}</li>
                                <li class="h5">Итого: <b style="color: #6610f2">{{$offer->total_price}}</b></li>
                                <li class="h5">Адрес доставки: {{$offer->cdek_info}}</li>
                                <li class="h5">Стоимость доставки: <b style="color: #6610f2">{{$offer->delivery_price}}</b></li>
                                <li class="h5">СДЭК ID: {{$offer->cdek_id}}</li>
                                <li class="h5">Дата создания заказа: {{$offer->created_at}}</li>
                            </ul>
                        </div>
            @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
