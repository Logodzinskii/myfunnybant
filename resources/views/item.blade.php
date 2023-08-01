<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @foreach($attributes['result'][0]['attributes'] as $attribute)
            @if($attribute['attribute_id'] == 4191 )
            <META NAME="description" content="{{preg_replace("/[a-zA-Z<>\\/&n#]+/", "", $attribute['values'][0]['value'])}}">
            @endif
        @endforeach
        <title>{{$result['result']['name']}}</title>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}"/>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href={{ asset('css/owl.carousel.min.css') }}>
        <link rel="stylesheet" href={{ asset('css/owl.theme.default.min.css') }}>
        <link rel="stylesheet" href="{{asset('css/myfunnybant.css')}}">

        <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
        <script src="{{asset('js/bootstrap.bundle.js')}}"></script>
        <script src={{ asset('js/owl.carousel.min.js')}}></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel(
                {
                    autoWidth: false,
                    dots: true,
                    margin:10,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
                }
            );

        });
    </script>
    </head>
    <body class="container-fluid">
        @include('header')
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                @foreach($result as $res)
                    <div class="col">
                        <h1>{{$res['name']}}</h1>
                        <div class="owl-carousel owl-theme owl-loaded side">
                            <div class="owl-stage-outer">
                                <div class="owl-stage">
                                    @foreach($res['images'] as $image)
                                        <div class="owl-item">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img src="{{$image}}" width="200" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h2>Информация</h2>
                            </div>
                            <div class="card-text">

                                @foreach($attributes['result'][0]['attributes'] as $attribute)
                                @if($attribute['attribute_id'] == 4191 )
                                        <div class="list-group-item"><p>{{preg_replace("/[a-zA-Z<>\\/&n#]+/", "", $attribute['values'][0]['value'])}}</p></div>
                                    @endif
                                @endforeach
                                    <ul class="list-group">
                                        <li class="list-group-item">ширина - {{$attributes['result'][0]['width']}} {{$attributes['result'][0]['dimension_unit']}}</li>
                                        <li class="list-group-item">высота - {{$attributes['result'][0]['height']}} {{$attributes['result'][0]['dimension_unit']}}</li>
                                        <li class="list-group-item">длинна - {{$attributes['result'][0]['depth']}} {{$attributes['result'][0]['dimension_unit']}}</li>
                                    </ul>
                                    @foreach($attributes['result'][0]['attributes'] as $attribute)
                                    @if($attribute['attribute_id'] == 10097)
                                        <div class="btn btn-outline-secondary">цвет - {{$attribute['values'][0]['value']}}</div>
                                            <div class="list-group-item" aria-current="true">
                                                <b style="font-size: xx-large">цена - {{ceil(intval($res['marketing_price']))}}.00 Р
                                                    <a href="{{route("seller.ozon", ['url'=>$attributes['result'][0]['name'].$attribute['values'][0]['value']])}}" class="btn btn-sm btn-outline-secondary">Перейти в озон</a>
                                                </b>
                                            </div>
                                    @endif
                                @endforeach
                        </div>
                        </div>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>

        @include('footer')
    </body>
</html>
