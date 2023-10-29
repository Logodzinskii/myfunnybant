@extends('admin.layouts.adminHome')
@section('content')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').on('change', function (e) {
               let val = $(this).val();
               let id = $(this).data('offer-id');
               $.ajax(
                   {   url:'/admin/update/status/offers/',
                       type:'put',
                       data: {
                           val:val,
                           id:id,
                           "_token": $('meta[name="csrf-token"]').attr('content')},
                   success: function(data){
                       let mess = $('body').find("[data-warning='" + id + "']");
                        if(data.success === true){
                            mess.removeClass('bg-warning');
                            mess.addClass('bg-success')
                            mess.text('выполнено');
                        }else{
                            mess.removeClass('bg-success');
                            mess.addClass('bg-warning')
                            mess.text(data.error);
                        }
                   },
                   error:function (error) {
                       console.log(error);
                   }
               })
            });
            $('.track').on('submit',function (e) {
                let val = $(this).val();
                let id = $(this).data('track-id');
                $.ajax(
                    {   url:'/admin/track/add',
                        type:'post',
                        data: $(this).serialize(),
                        success: function(data){
                        console.log(data);
                            let mess = $('body').find("[data-track-message='" + id + "']");
                            if(data.success === true){
                                mess.removeClass('bg-warning');
                                mess.addClass('bg-success')
                                mess.text('выполнено');
                            }else{
                                mess.removeClass('bg-success');
                                mess.addClass('bg-warning')
                                mess.text(data.error);
                            }
                        },
                        error:function (error) {
                            console.log(error);
                        }
                    })
                return false;
            })
        })
    </script>
    <div class="container">
        <div class="ms-3 me-3 bg-light shadow rounded-2 p-2">
            <form method="post" action="{{url('/admin/view/offers')}}">
                @csrf

                    @foreach(\Illuminate\Support\Facades\DB::table('offer_users')->groupBy('status')->get() as $key=>$status)
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio{{$key}}" value="{{$status->status}}">
                        <label class="form-check-label ms-3 me-3" for="inlineRadio{{$key}}">
                                    {{$status->status}}
                            <span class="badge text-bg-secondary position-absolute rounded-circle">
                            {{\App\Models\OfferUser::where('status','=',$status->status)->count()}}
                            </span>
                        </label>
                    </div>
                    @endforeach
                <button type="submit" class="btn btn-outline-primary" >Применить</button>
        </form>
    </div>
<table class="table">
<thead>
<tr>
<th scope="col">№ заказа</th>
<th scope="col">Заказчик</th>
<th scope="col">Доставка</th>
<th scope="col">Статус</th>
<th scope="col">Номер трэка</th>
</tr>
</thead>
<tbody>
@foreach($carts as $cart)
<tr>
<th scope="row">{{$cart->id}}</th>
<td>
    <ul>
        <li>{{$cart->name}}</li>
        <li><a href="mailto:'{{$cart->email}}'">{{$cart->email}}</a></li>
        <li>{{$cart->tel}}</li>
    </ul>
</td>
<td>
    @foreach(\App\Models\UserCart::where('offer_id','=', $cart->id)->get() as $offer)
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Картинка
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="col" style="width: 10vw">
                                <img src="{{json_decode(\App\Models\OzonShopItem::where('ozon_id','=',$offer->ozon_id)->firstOrFail()->images, true)[0]['file_name']}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Доставка: {{$offer->cdek_info}}
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li>Озон ID: {{$offer->ozon_id}}</li>
                                <li >Адрес доставки: {{$offer->cdek_info}}</li>
                                <li >Стоимость доставки: <b style="color: #6610f2">{{$offer->delivery_price}}</b></li>
                                <li >СДЭК ID: {{$offer->cdek_id}}</li>
                                <li >Дата создания заказа: {{$offer->created_at}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            На сумму {{$offer->total_price}}
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li >Количество: <b style="color: #6610f2">{{$offer->quantity}}</b></li>
                                <li >Цена за единицу: {{$offer->price}}</li>
                                <li >Итого: <b style="color: #6610f2">{{$offer->total_price}}</b></li>
                                <li>User ID: {{$offer->user_id}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
</td>
<td>
    <select class="select form-select" aria-label="Default select example" data-offer-id="{{$cart->id}}">
        <option value="{{$cart->status}}">{{$cart->status}}</option>
        <option value="Новый">Новый</option>
        <option value="Подтвержден">Подтвержден</option>
        <option value="Оплата получена">Оплата получена</option>
        <option value="Посылка отправлена">Посылка отправлена</option>
        <option value="Покупатель отказался">Покупатель отказался</option>
        <option value="Оплата не поступила">Оплата не поступила</option>
    </select>
    <hr>
    @if($cart->confirm == 'подтвержден')
        <div class="bg-success rounded-2 p-2">{{$cart->confirm}}</div>
    @else
       <div class="bg-warning rounded-2 p-2">Не подтвержден</div>
    @endif
    <div class="error rounded-2 p-2" data-warning="{{$cart->id}}"></div>
</td>
<td>
    <form class="track" method="post" action="{{url('/admin/track/add')}}" data-track-id="{{$cart->id}}">
        @csrf
        <input type="text" name="val" value="{{$cart->offer}}" required>
        <input type="hidden" name="id" value="{{$cart->id}}">
        <button type="submit">отправить</button>
    </form>
    <div class="track-message rounded-2 p-2" data-track-message="{{$cart->id}}"></div>
</td>
</tr>
@endforeach
</tbody>
</table>

</div>
@endsection
