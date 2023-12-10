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
    <div class="container overflow-scroll">
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
        <div class="container  ">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3 basic-staggering-demo">
            @foreach($carts as $cart)
            <div class="card mb-3 mt-3 p-1 border-bottom border-secondary">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingUserOne{{$cart->id}}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUserOne{{$cart->id}}" aria-expanded="true" aria-controls="collapseUserOne{{$cart->id}}">
                                Заказ {{$cart->id}}
                            </button>
                        </h2>
                        <div id="collapseUserOne{{$cart->id}}" class="accordion-collapse collapse show" aria-labelledby="headingUserOne{{$cart->id}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul>
                                    <li>{{$cart->name}}</li>
                                    <li><a href="mailto:'{{$cart->email}}'">{{$cart->email}}</a></li>
                                    <li>{{$cart->tel}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach(\App\Models\UserCart::where('offer_id','=', $cart->id)->get() as $offer)
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne{{$cart->id}}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$cart->id}}" aria-expanded="true" aria-controls="collapseOne{{$cart->id}}">
                                    Картинка
                                </button>
                            </h2>
                            <div id="collapseOne{{$cart->id}}" class="accordion-collapse collapse show" aria-labelledby="headingOne{{$cart->id}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="col" style="width: 10vw">
                                        <img src="{{json_decode(\App\Models\OzonShopItem::where('ozon_id','=',$offer->ozon_id)->firstOrFail()->images, true)[0]['file_name']}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo{{$cart->id}}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo{{$cart->id}}" aria-expanded="false" aria-controls="collapseTwo{{$cart->id}}">
                                    Доставка: {{$offer->cdek_info}}
                                </button>
                            </h2>
                            <div id="collapseTwo{{$cart->id}}" class="accordion-collapse collapse" aria-labelledby="headingTwo{{$cart->id}}" data-bs-parent="#accordionExample">
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
                            <h2 class="accordion-header" id="headingThree{{$cart->id}}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree{{$cart->id}}" aria-expanded="false" aria-controls="collapseThree{{$cart->id}}">
                                    На сумму {{$offer->total_price}}
                                </button>
                            </h2>
                            <div id="collapseThree{{$cart->id}}" class="accordion-collapse collapse" aria-labelledby="headingThree{{$cart->id}}" data-bs-parent="#accordionExample">
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
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingStatusOne{{$cart->id}}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStatusOne{{$cart->id}}" aria-expanded="true" aria-controls="collapseStatusOne{{$cart->id}}">
                                Статус
                            </button>
                        </h2>
                        <div id="collapseStatusOne{{$cart->id}}" class="accordion-collapse collapse show" aria-labelledby="headingStatusOne{{$cart->id}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <select class="select form-select" aria-label="Default select example" data-offer-id="{{$cart->id}}">
                                    <option value="{{$cart->status}}">{{$cart->status}}</option>
                                    <option value="Новый">Новый</option>
                                    <option value="Подтвержден">Подтвержден</option>
                                    <option value="Оплата получена">Оплата получена</option>
                                    <option value="Посылка отправлена">Посылка отправлена</option>
                                    <option value="Покупатель отказался">Покупатель отказался</option>
                                    <option value="Оплата не поступила">Оплата не поступила</option>
                                </select>
                                @if($cart->confirm == 'подтвержден')
                                    <div class="bg-success rounded-2 p-2">{{$cart->confirm}}</div>
                                @else
                                    <div class="bg-warning rounded-2 p-2">Не подтвержден</div>
                                @endif
                                <div class="error rounded-2 p-2" data-warning="{{$cart->id}}"></div>
                            </div>
                        </div>
                        <h2 class="accordion-header" id="headingStatusTwo{{$cart->id}}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStatusTwo{{$cart->id}}" aria-expanded="true" aria-controls="collapseStatusTwo{{$cart->id}}">
                                Номер трэка СДЭК
                            </button>
                        </h2>
                        <div id="collapseStatusTwo{{$cart->id}}" class="accordion-collapse collapse show" aria-labelledby="headingStatusTwo{{$cart->id}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form class="track" method="post" action="{{url('/admin/track/add')}}" data-track-id="{{$cart->id}}">
                                    @csrf
                                    <input type="text" name="val" value="{{$cart->offer}}" required>
                                    <input type="hidden" name="id" value="{{$cart->id}}">
                                    <button type="submit">отправить</button>
                                </form>
                                <div class="track-message rounded-2 p-2" data-track-message="{{$cart->id}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            @endforeach
            </div>
        </div>
</div>
@endsection
