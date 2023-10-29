@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-offer').on('click',function (){
                let id = $(this).data('delete-offer-id')
                $.post('/user/delete/offer',
                    {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    },function (data) {
                    $('body').find(`[data-delete-offer-id='` + data + `']`).parent().parent().remove();
                    alert(data);
                });
            })
        })
    </script>
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @foreach($carts as $cart)
            @if(count(\App\Models\UserCart::where('offer_id','=',$cart->id)->groupBy('offer_id')->get())==0)
                заказов нет
            @else
                <div class="card ">
                    <ul class="card-header list-unstyled">
                        <li class="h4" style="color: #6610f2"><i class="bi-basket3"></i>Номер заказа: {{$cart->id}}</li>
                        <li class="h4">Имя заказчика: {{\App\Models\OfferUser::where('session_user','=', $cart->session_user)->firstOrFail()->name}}</li>
                        <li class="h4">Email: <a href="mailto:'{{\App\Models\OfferUser::where('session_user','=', $cart->session_user)->firstOrFail()->email}}'">{{\App\Models\OfferUser::where('session_user','=', $cart->session_user)->firstOrFail()->email}}</a></li>
                        <li class="h4">Tel: {{$cart->tel}}</li>
                        <li class="h4">Статус заказа:
                            <ul>
                                @php
                                $arr = [
                                    'Новый',
                                    'Подтвержден',
                                    'Оплата получена',
                                    'Посылка отправлена',
                                ];
                                $i = 0;
                                @endphp
                                @foreach($arr as $key => $item)
                                        @if(\App\Models\OfferUser::where('id','=',$cart->id)->get()[0]->status == $item)
                                        <li style="color: mediumvioletred">{{$item}}</li>

                                    @else
                                        @if($i >= $key)
                                        <li style="color: green">{{$item}}</li>
                                            @php
                                                $i++
                                            @endphp
                                        @else
                                            <li style="color: grey">{{$item}}</li>
                                            @endif
                                        @endif
                                    @endforeach
                            </ul>
                        </li>
                    </ul>
                    <div class="m-1 container row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                        @foreach(\App\Models\UserCart::where('offer_id','=', $cart->id)->get() as $offer)
                            <div class="shadow row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                                <div class="col-4 p-3">
                                    <img src="{{json_decode(\App\Models\OzonShopItem::where('ozon_id','=',$offer->ozon_id)->firstOrFail()->images, true)[0]['file_name']}}" />
                                </div>
                                <ul class="col-6 p-3 list-unstyled">
                                    <li class="h5">Номер позиции: {{$offer->id}}</li>
                                    <li class="h5">Количество: <b style="color: #6610f2">{{$offer->quantity}}</b></li>
                                    <li >Цена за единицу: {{$offer->price}}</li>
                                    <li class="h5">Итого: <b style="color: #6610f2">{{$offer->total_price}}</b></li>
                                <!--<li class="h5">Адрес доставки: {{$offer->cdek_info}}</li>
                                    <li class="h5">Стоимость доставки: <b style="color: #6610f2">{{$offer->delivery_price}}</b></li>
                                    <li class="h5">СДЭК ID: {{$offer->cdek_id}}</li>
                                    <li class="h5">Дата создания заказа: {{$offer->created_at}}</li>-->
                                </ul>
                            <!--<div class="card-footer">
                                    <button type="button" class="btn text-light delete-offer" style="background-color: #6610f2" data-delete-offer-id="{{$offer->id}}">
                                        Удалить позицию <i class="bi-trash3 p-3" style="color: #ffffff; font-size: 2em;"></i>
                                    </button>
                                </div>-->
                            </div>
                        @endforeach
                    </div>
                    <div class="shadow p-3">
                        <h2>
                            Адрес доставки: {{\App\Models\UserCart::where('offer_id','=',$cart->id)->groupBy('offer_id')->get()[0]->cdek_info}}
                        </h2>
                        <h2>
                            Стоимость доставки<span style="color: mediumvioletred">*</span>: {{\App\Models\UserCart::where('offer_id','=',$cart->id)->groupBy('offer_id')->get()[0]->delivery_price}} руб.
                        </h2>
                        <p><span style="color: mediumvioletred">*</span>Доставка оплачивается заказчиком в пункте выдачи СДЭК</p>
                    </div>
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
            @endif

        @endforeach
            <div class="card-footer">

                <!--<button type="button" class="btn text-light" style="background-color: #6610f2">
                    Удалить заказ <i class="bi-trash3 p-3 delete-cart" style="color: #ffffff; font-size: 2em;"></i>
                </button>
                <button type="button" class="btn text-light mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal" style="background-color: #6610f2">
                    Оплатить заказ <i class="bi-credit-card p-3" style="color: #ffffff; font-size: 2em"></i>
                    {{$totalSum}}
                </button>-->
            </div>
    </div>
@endsection
