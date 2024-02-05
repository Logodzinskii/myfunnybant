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
<div class="">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#id</th>
            <th scope="col">ozon_id</th>
            <th scope="col">Картинка</th>
            <th scope="col">Номер категории и тип</th>
            <th scope="col">Заголовок и описание</th>
            <th scope="col">Характеристики</th>
            <th scope="col">Создание и обновление</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <th scope="row">{{$product->id}}</th>
                <td>{{$product->ozon_id}}</td>
                <td style="width: 10vw; height: 10vh"><img src="{{json_decode($product->images,true)[0]['file_name']}}" alt=""></td>
                <td>
                    {{$product->category}}
                    <hr>
                    {{$product->type}}
                    <hr>
                    Цены
                    @foreach(\App\Models\StatusPriceShopItems::where('ozon_id', '=', $product->ozon_id)->get() as $price)
                        без скидки <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light">{{$price->price}}</div>
                        по акции озон <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light"> {{$price->action_price}}</div>
                    @endforeach
                </td>
                <td>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$product->id}}" aria-expanded="true" aria-controls="collapse{{$product->id}}">
                                    {{$product->header}}
                                </button>
                            </h2>
                            <div id="collapse{{$product->id}}" class="accordion-collapse collapse " data-bs-parent="#accordionExample{{$product->id}}">
                                <div class="accordion-body">
                                    {{$product->description}}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div >
                        <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light">ширина - {{$product->width}}</div>
                        <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light">высота - {{$product->height}}</div>
                        <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light">глубина - {{$product->depth}}</div>
                        @foreach(json_decode($product->material, true) as $material)
                            <div class="p-1 mb-1 mt-1 bg-primary rounded-2 text-light">{{$material}}</div>
                        @endforeach
                    </div>
                </td>
                <td>
                    Обновлен - {{$product->updated_at->format('j F, Y')}}
                    <hr>
                    Создан - {{$product->created_at->format('j F, Y')}}
                    <hr>
                    <a href="/admin/Barcod/create/{{$product->ozon_id}}"> BarCod</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
