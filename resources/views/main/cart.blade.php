@extends('layouts.app')
@section('content')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function (){

        })
    </script>
<section class="col-lg-12">
    <h1>Мои заказы</h1>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">
    @foreach($cart as $lid)

                <div class="card col">
                    <div class="card-title">{{$lid->associatedModel['name']}}</div>
                    <img src="{{json_decode($lid->associatedModel['images'],true)[0]['file_name']}}" class="img-thumbnail" width="300px" />
                    <div class="card-footer">
                        Количество:
                        <div class="updateQuantity"  style="color: #6610f2; font-size: 1.4em">
                            <span data-idres="{{$lid->id}}">{{$lid->quantity}}</span>
                            <span class="update plus" data-id="{{$lid->id}}">
                                <i class="bi-plus-circle m-3 "></i>
                            </span>
                            <span class="update minus" data-id="{{$lid->id}}">
                                <i class="bi-dash-circle"></i>
                            </span>
                        </div>
                        <span>Цена за единицу товара:</span><span data-price-item="{{$lid->id}}">{{ $lid->price}}</span><span> &#8381;</span>
                        <span>Итого:</span><span data-price="{{$lid->id}}">{{$lid->quantity * $lid->price}}</span>
                        <div class="delete-item-cart" data-delete="{{$lid->id}}" style="color: #6610f2; font-size: 1.4em">
                            <i class="bi-trash"></i>
                        </div>
                    </div>
                </div>
    @endforeach
        </div>
    </div>
</section>
@endsection
