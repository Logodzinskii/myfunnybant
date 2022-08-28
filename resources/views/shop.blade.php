<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="description">
        <title>Myfunnybant - аксессуары для волос</title>
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

                    margin:10,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:2
                        },
                        1000:{
                            items:3
                        }
                    }
                }
            );

        });
    </script>
    </head>
    <body class="container-fluid">
        @include('header')

    <section style="min-height: 100vh">
        <div class="card mb-3 h-100">
            <h1 class="card-title text-center">Аксессуары для волос ручной работы</h1>
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{asset('images/logo/logo.png')}}" width="100%"  alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h1 class="card-title">В моем магазине вы можете выбрать и заказать</h1>
                        <ul class="list-group">
                            @foreach($data['category']['type'] as $type => $count)
                                <li class="list-group-item"><a href="/shop/category/{{$type}}" class="btn btn-light">{{$type}} - {{$count}}</a></li>
                            @endforeach
                        </ul>
                        <p>Все работы, представленные в моем магазине сделаны с любовью</p>
                        <p>Я использую только проверенные мной материалы</p>
                        <p>Мои работы высокого качества и служат долго</p>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="card-title text-center">Посмотрите мои работы</h1>
    </section>
        <div class="row">
            <section class="col-lg-2">
                <nav class="navbar sticky-top navbar-light bg-light">
                    <div class="container-fluid">
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Выбор цвета
                            </a>

                            <ul class="dropdown-menu">
                                @foreach($data['category']['colors'] as $color => $count)
                                    <div class="input-group mb-3 dropdown-item">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="checkbox" value="{{$color}}" aria-label="Checkbox for following text input">
                                            <span>{{$color}} - {{$count}}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </nav>
            </section>
            <section class="col-lg-10">
                <section class="container-md">
                    <!--<div class="card">
                        <div class="card-body">
                            <div class="card-body">
                                <h1 class="card-title">Резинки для волос</h1>
                                <p>Резинка для волос это эластичное кольцо для собирания волос и создания прически.</p>
                                <p>В основном резинка для волос предназначается для того, чтобы предотвратить попадание волос в глаза или механическую технику во время домашней работы.</p>
                            </div>
                        </div>
                    </div>-->
                    @foreach($data['bant'] as $items)
                    <div class="owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                @foreach($items as $item)
                                    <div class="owl-item">
                                        <h5 style="height: 75px;">{{$item['name']}}</h5>
                                        <img src="{{$item['images'][0]['file_name']}}" class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </section>
            </section>
        </div>
    </body>
</html>
