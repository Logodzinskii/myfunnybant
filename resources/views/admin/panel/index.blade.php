@extends('admin.layouts.adminHome')
@section('content')
    <section class="container">
        <h2>Панель администратора сайта</h2>
        <div class="container d-flex justify-content-start flex-wrap" >
            <div class="card">
                <div class="card-header">
                    Всего товаров на сайте:
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <i class="bi-card-list" style="font-size: 1.7em; color: #6610f2"></i>
                    <b><a href="admin/show/all/products" class="m-3">{{\App\Models\OzonShopItem::all()->count()}}</a></b>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Последнее обновление товаров на сайте:
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <i class="bi-arrow-clockwise" style="font-size: 1.7em; color: #6610f2"></i>
                    <b class="m-3">{{\App\Models\OzonShopItem::query()->select('updated_at')->firstOrFail()->updated_at}}</b>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Средняя цена товаров на сайте
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <ul>
                        <i class="bi-cash-coin" style="font-size: 1.7em; color: #6610f2"></i>
                        <b class="m-3">{{\App\Models\StatusPriceShopItems::all()->average('price')}}</b>
                        руб.
                    </ul>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    Средняя цена товаров на сайте с учетом скидок
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <i class="bi-cash-coin" style="font-size: 1.7em; color: #6610f2"></i>
                    <b class="m-3">{{\App\Models\StatusPriceShopItems::all()->average('action_price')}}</b>
                    руб.
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Заказы:
                </div>
                <div class="card-body d-flex justify-content-center align-items-center flex-wrap">
                    @foreach(\Illuminate\Support\Facades\DB::table('offer_users')
                                                                    ->select('status')
                                                                    ->groupBy('status')
                                                                    ->get() as $offer)
                        <div class="m-3">
                            {{$offer->status}} -
                            {{\App\Models\OfferUser::query()
                                            ->select('status')
                                            ->where('status','=',$offer->status)
                                            ->count()
                            }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Список пользователей:
                </div>
                <div class="card-body">
                    <ul>
                        @foreach(\App\Models\User::all() as $email)
                            <li>имя - {{$email->first_name.$email->name}}, email - {{$email->email}}, создан - {{$email->created_at}} <a href="#">del</a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endsection
