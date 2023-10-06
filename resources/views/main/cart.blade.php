@extends('layouts.app')
@section('content')
<section class="container">
    <h1>Мои заказы</h1>
    <div>
        <table class="table">
            <thead>
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Картинка</th>
                <th scope="col">Количество</th>
                <th scope="col">За ед.</th>
                <th scope="col">Итого</th>
                <th scope="col">Удалить</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart as $lid)
            <tr>
                <th scope="row">{{$lid->id}}</th>
                <td class="w-25">{{$lid->associatedModel['name']}}</td>
                <td class="w-25"><img src="{{json_decode($lid->associatedModel['images'],true)[0]['file_name']}}" class="img-thumbnail" /></td>
                <td><div class="updateQuantity d-flex flex-wrap justify-content-center"  style="color: #6610f2; font-size: 1.4em">
                        <div>
                            <span data-idres="{{$lid->id}}">{{$lid->quantity}}</span>
                        </div>
                        <div>
                            <span class="update plus" data-id="{{$lid->id}}">
                                <i class="bi-plus-circle m-3 "></i>
                            </span>
                            <span class="update minus" data-id="{{$lid->id}}">
                                <i class="bi-dash-circle"></i>
                            </span>
                        </div>
                    </div>
                </td>
                <td><span data-price-item="{{$lid->id}}">
                        {{ $lid->price}}
                    </span>
                </td>
                <td><span data-price="{{$lid->id}}">
                        {{$lid->quantity * $lid->price}}
                    </span>
                </td>
                <td>
                    <div class="delete-item-cart" data-delete="{{$lid->id}}" style="color: #6610f2; font-size: 1.4em">
                        <i class="bi-trash"></i>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>



        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">

        </div>
    </div>
    <h2>Оплата</h2>
    <h2>Доставка</h2>
    <input type="text" name="pvz" placeholder="Код ПВЗ">
    <input type="text" name="address" placeholder="Адрес ПВЗ">

    <script id="ISDEKscript" type="text/javascript" src="https://widget.cdek.ru/widget/widjet.js" charset="utf-8"></script>
    <script>
        $(document).on('ready', function(){
            var widjet = new ISDEKWidjet({
                hidedelt: true,
                defaultCity: 'Екатеринбург',
                cityFrom: 'Екатеринбург',
                link: 'forpvz',
                onChoose: function(info){
                    ipjq('[name="pvz"]').val(info.id);
                    ipjq('[name="address"]').val(' г.' + info.cityName + ', ' + info.PVZ.Address);

                    var obj = info;
                    var arr = Object.keys(obj).map(function (key) { return obj[key]; });
                    console.log(arr);
                    $('.delivery-city').html(arr[7]);
                    $('.delivery-adress-cdek').html(' ул. ' + arr[1]['Address']);
                    $('.delivery-price').html(' Стоимость доставки ' +arr[6] + ' доставка оплачивается в пункте выдачи СДЭК');
                    $('input[name=input_delivery_city]').val(arr[7]);
                    $('input[name=input_delivery_adress_cdek]').val(arr[1]['Address']);
                    $('input[name=input_delivery_price]').val(arr[6]);
                }
            });
        })
    </script>

    <div id="forpvz" style="height:500px;"></div>

    <h2>Сведения о вашем заказе</h2>
    Всего товаров <span class="total"></span>
    На сумму <span class="totalSum"></span>
    <h2>Посылка будет отправлена по адресу:</h2>
    <div class="delivery-city h3"></div>
    <div class="delivery-adress-cdek h3"></div>
    <div class="delivery-price h3"></div>
    <h2>Получатель</h2>
    <div>
        <h3>Имя: {{Illuminate\Support\Facades\Auth::user()->name}}</h3>
        <h3>e-mail: {{Illuminate\Support\Facades\Auth::user()->email}}</h3>
        <h3>телефон:</h3>
    </div>
    <form action="{{route('user.create.offer')}}" method="post">
        @csrf
        <input name="input_delivery_city" type="hidden" value="" />
        <input name="input_delivery_adress_cdek" type="hidden" value="" />
        <input name="input_delivery_price" type="hidden" value="" />
        @php
        $arrOzonId = [];
        foreach ($cart as $lid){
                   $arrOzonId[] = [
                       'ozon_id'=>$lid->associatedModel['ozon_id'],
                       'quantity'=>$lid->quantity,
                       'price'=>$lid->price,
                       'total_price'=>$lid->quantity * $lid->price
                       ];

        }
        @endphp
        <input name="ozon_id" type="hidden" value="{{json_encode($arrOzonId)}}" />
        <button id="reateOffer" type="submit" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Оформить заказ</button>
    </form>
</section>
@endsection
