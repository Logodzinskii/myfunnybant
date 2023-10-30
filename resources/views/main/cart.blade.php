@extends('layouts.app')
@section('content')
<section class="container basket">
    <h1>Мои заказы</h1>
    @if(count($cart)>0)

    <div class="container d-flex flex-wrap ">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
            <div class="col">
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
                            <td>
                                <div class="updateQuantity d-flex flex-nowrap justify-content-center"  style="color: #6610f2; font-size: 1.2em">
                                    <span class="update minus" data-id="{{$lid->id}}">
                                        <i class="bi-dash-circle m-1"></i>
                                    </span>
                                    <div>
                                        <span class="m-1 text-center" data-idres="{{$lid->id}}">{{$lid->quantity}}</span>
                                    </div>
                                    <div>
                                    <span class="update plus" data-id="{{$lid->id}}">
                                        <i class="bi-plus-circle m-1 "></i>
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
            </div>
            <div class="col">
                <div class="shadow p-1 m-1">
                    <span><b>Ваш заказ в</b></span>
                    <span><b>Интернет-магазине Myfunnybant.ru</b></span>
                <hr>
                    <div class="d-flex justify-content-between">
                        <span>Всего товаров</span>
                        <span class="total" style="color: mediumvioletred; size: 2em"></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>На сумму</span>
                        <span class="totalSum" style="color: mediumvioletred; size: 2em"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    $('.delivery-adress-cdek').html(arr[1]['Address']);
                    $('.delivery-price').html(' Стоимость доставки ' +arr[6] + 'руб. доставка оплачивается в пункте выдачи СДЭК');
                    $('.totalDelivery').html(arr[6]);
                    $('input[name=input_delivery_city]').val(arr[7]);
                    $('input[name=input_delivery_adress_cdek]').val(arr[1]['Address']);
                    $('input[name=input_delivery_price]').val(arr[6]);
                    $('input[name=input_CDEK_id]').val(arr[0]);
                    $('#message-delivery-denied').css('display','none');
                    $('#message-delivery-allow').removeClass('.disabled');

                }
            });
            $('.offer-start').on('click',function(){

                $('.modal').show();
                $('.close').on('click', function(){
                    $('.modal').hide();
                })
            })
            $('form').on('submit',function (e) {
                $('body').css('cursor','wait')
                $.ajax({
                    url: "{{url('/user/create/offer')}}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function( data ) {
                        if(data.success === true){
                            $('.myerror').html('');
                            //$('.count-offer').html(data);
                            console.log(data);
                            $('body').css('cursor','default');
                            $('.modal').hide();
                            $('.basket').html(data);
                            window.location.href = '{{url('/user/confirm/')}}';
                        }

                    },
                    error: function (exception){
                        $('.myerror').html('');
                        let error = JSON.parse(exception['responseText']);
                        $.each((error['errors']),function(key, value){
                            console.log(key + ': ' + value);
                            $('.myerror').append('<span class="p-2 m-1 bg-warning h-4 rounded-3 shadow">'+ key + ': ' + value +'</span>');
                            $('body').css('cursor','default');
                        });
                    }
                });
                e.preventDefault()
            });
        })
    </script>
    <button class="my-button btn btn-sm g-2 h-4 m-1 text-white offer-start">
        Продолжить оформление заказа
    </button>
</section>
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Оплата заказа</h1>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2>Доставка и оплата</h2>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                1. Выбор пункта доставки СДЭК
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="flex-wrap d-flex justify-content-between">
                                   <div >
                                       <input type="text" name="pvz" placeholder="Код ПВЗ">
                                       <input type="text" name="address" placeholder="Адрес ПВЗ">
                                       <div id="forpvz" style="height:550px; max-width: 500px"></div>
                                   </div>
                                    <div class="shadow p-1 m-1 ">
                                        <h2>Доставка:</h2>
                                        <p>Уважаемые покупатели!</p>
                                        <p>Мы осуществляем доставку товаров для рукоделия по ВСЕЙ РОССИИ.</p>
                                        <p>В нашем интернет-магазине Myfunnybant имеется несколько вариантов доставки:</p>
                                        <ul>
                                            <li>ТК СДЭК до пункта выдачи заказов</li>
                                        </ul>
                                        <p>Стоимость доставки рассчитывается индивидуально для каждого заказа и зависит от веса и Вашего местонахождения.
                                            Оплата за услуги доставки ТК СДЭК осуществляется при получении заказа.
                                            Обращаем Ваше внимание, что наш интернет-магазин не несет ответственность за сроки доставки Почтой России и ТК СДЭК.
                                            Сроки могут быть уменьшены или увеличены в связи с загруженностью той или иной транспортной компании.
                                        </p>
                                        <p id="message-delivery-denied">Для примерного расчета стоимости доставки ТК СДЭК
                                            выберите пункт выдачи на карте
                                        </p>
                                        <div id="message-delivery-allow" class="disabled">
                                            <div class="delivery-city h3"></div>
                                            <div class="delivery-adress-cdek h3"></div>
                                            <div class="delivery-price h3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                2. Оплата
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <h2>Оплата</h2>
                                <p>Реквизиты на оплату высылаем на Ваш e-mail
                                    ПОСЛЕ сборки Вашего заказа (в течение 24 часов после оформления заказа на сайте)
                                </p>

                                <p>Мы работаем по 100% предоплате.</p>

                                <p>МИНИМАЛЬНОЙ СУММЫ ЗАКАЗА НЕТ.</p>

                                E-mail: <a href="mailto:veronika-manager@mail.ru" >veronika-manager@mail.ru</a>

                                <p>Режим работы:</p>
                                <p>Приём заказов — ЕЖЕДНЕВНО.</p>
                                <p>Сборка и отгрузка заказов: Пн-пт с 10.00 до 20.00.</p>

                                <p>ЗАКАЗЫ, НЕОПЛАЧЕННЫЕ В ТЕЧЕНИЕ 2-Х РАБОЧИХ ДНЕЙ, АВТОМАТИЧЕСКИ АННУЛИРУЮТСЯ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container d-flex justify-content-between flex-wrap m-3 p-1">
                    <div class="shadow p-1 m-1">
                        <div class="">
                            <span><b>Ваш заказ в</b></span>
                            <span><b>Интернет-магазине Myfunnybant.ru</b></span>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Всего товаров</span>
                                <span class="total" style="color: mediumvioletred; size: 2em"></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>На сумму</span>
                                <span class="totalSum" style="color: mediumvioletred; size: 2em"></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Доставка СДЭК <br> (оплачивается при получении)</span>
                                <span class="totalDelivery" style="color: mediumvioletred; size: 2em">Выберите на карте</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Итого <br> к переводу на карту</span>
                                <span class="totalSum" style="color: mediumvioletred; size: 2em"></span>
                            </div>
                        </div>
                    </div>
                    <div class="shadow p-2">
                        <form  method="post" name="offerForm">
                        <h2>Информация о получателе заказа</h2>
                        <h3>Имя<span style="color: red">*</span>: <input name="first_name" type="text" placeholder="Имя" value="{{session()->exists('user')?session('user')['name']:''}}" required/></h3>
                        <h3>e-mail<span style="color: red">*</span>: <input name="email" type="email" placeholder="example@gmail.com" value="{{session()->exists('user')?session('user')['email']:''}}" required ></h3>
                        <h3>Телефон<span style="color: red">*</span>: <input name="tel" type="tel" placeholder="XXXXXXXXXX" value="{{session()->exists('user')?session('user')['tel']:''}}" required></h3>
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <span class="myerror d-flex flex-wrap">

                            </span>

                            <h3><span style="color: red">*</span> - обязательно для заполнения</h3>
                            @csrf
                            <input name="input_delivery_city" type="hidden" value="{{session()->exists('delivery')?session('delivery')['delivery_city']:''}}" />
                            <input name="input_delivery_adress_cdek" type="hidden" value="{{session()->exists('delivery')?session('delivery')['delivery_adress_cdek']:''}}" />
                            <input name="input_delivery_price" type="hidden" value="{{session()->exists('delivery')?session('delivery')['delivery_price']:''}}" />
                            <input name="input_CDEK_id" type="hidden" value="{{session()->exists('delivery')?session('delivery')['CDEK_id']:''}}" />
                            <button id="сreateOffer" type="submit" class="my-button btn btn-sm g-2 h-4 m-1 text-white">Купить</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    @else
        <div class="basket-null">
            <h2>Ваша корзина пуста</h2>
            <a href="/">Перейти к покупкам</a>
        </div>
    @endif
</div>
@endsection
