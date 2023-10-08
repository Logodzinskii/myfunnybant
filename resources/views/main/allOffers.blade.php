@extends('layouts.app')
@section('content')
    <div class="container">
        @foreach($carts as $cart)
            <div class="card ">
                <ul class="card-header list-unstyled">
                    <li class="h4" style="color: #6610f2"><i class="bi-basket3"></i>Номер заказа: {{$cart->id}}</li>
                    <li class="h4">Имя заказчика: {{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->name}}</li>
                    <li class="h4">Email: <a href="mailto:'{{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->email}}'">{{\App\Models\User::where('id','=', $cart->user_id)->firstOrFail()->email}}</a></li>
                    <li class="h4">Tel: </li>
                    <li class="h4">Статус заказа: <b style="color: mediumvioletred">Ожидает оплаты</b> <i style="color: #6610f2" class="bi-caret-down-square"></i> </li>
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
                <div class="card-footer">
                    Удалить заказ <i class="bi-trash3 p-3" style="color: #6610f2; font-size: 2em"></i>
                    <button type="button" class="btn text-light" data-bs-toggle="modal" data-bs-target="#exampleModal" style="background-color: #6610f2">
                        Оплатить заказ <i class="bi-credit-card p-3" style="color: #ffffff; font-size: 2em"></i>
                    </button>

                </div>
                <!-- Button trigger modal -->


                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Оплата заказа</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Перевод на карту через приложение банка
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                Зайдите в свой интернет-банк или мобильное приложение банка, где у вас открыт счет.
                                                Выберите раздел “Переводы” или “Платежи и переводы”.
                                                Введите номер карты получателя и сумму перевода.
                                                Проверьте правильность введенных данных и подтвердите перевод.
                                                Дождитесь уведомления о том, что перевод успешно выполнен.
                                                Сохраните квитанцию о переводе на случай, если потребуется подтвердить факт оплаты.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Accordion Item #2
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Accordion Item #3
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
