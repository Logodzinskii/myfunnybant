@extends('admin.layouts.adminHome')
@section('content')
    <section class="container">
        <h2>Панель администратора сайта</h2>
        <ul>
            <li>
                Всего товаров на сайте:
                <b>{{\App\Models\OzonShopItem::all()->count()}}</b>
            </li>
            <li>
                Последнее обновление товаров на сайте:
                <b>{{\App\Models\OzonShopItem::query()->select('updated_at')->firstOrFail()->updated_at}}</b>
            </li>
            <li>
                Список пользователей:
                <ul>
                    @foreach(\App\Models\User::all() as $email)
                        <li>имя - {{$email->first_name.$email->name}}, email - {{$email->email}}, создан - {{$email->created_at}} <a href="#">del</a> </li>
                    @endforeach
                </ul>
            </li>
            <li>
                Средняя цена товаров на сайте
                <b>{{\App\Models\StatusPriceShopItems::all()->average('price')}}</b>
                руб.
            </li>
            <li>
                Средняя цена товаров на сайте с учетом скидок
                <b>{{\App\Models\StatusPriceShopItems::all()->average('action_price')}}</b>
                руб.
            </li>
            <ul>
                <li>
                    Заказы:
                    @foreach(\App\Models\UserCart::query()->select('status_offer')->groupBy('status_offer')->get() as $offer)
                        {{$offer->status_offer}} -
                        {{\App\Models\UserCart::query()
                                            ->select('quantity')
                                            ->where('status_offer','=',$offer->status_offer)
                                            ->count()}}
                    @endforeach
                </li>
            </ul>
        </ul>
    </section>


    @endsection
