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

        <script src={{ asset('js/owl.carousel.min.js')}}></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel(
                {
                    loop:true,
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
        <div class="row">
            <section class="col-lg-2">
                <nav class="navbar sticky-top navbar-light bg-light">
                    <div class="container-fluid">
                        @foreach($data['category']['type'] as $type => $count)
                            <a href="/shop/category/{{$type}}" class="btn btn-light">{{$type}} - {{$count}}</a>
                        @endforeach
                        @foreach($data['category']['colors'] as $color => $count)
                        <a href="/shop/color/{{$color}}" class="btn btn-primary">{{$color}} - {{$count}}</a>
                        @endforeach
                    </div>
                </nav>
            </section>
            <section class="col-lg-10">
                <h1 class="card-title">Аксессуары для волос ручной работы</h1>
                <section class="container-md">
                    <div class="card mb-3" >
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{asset('images/shop_images/photo1659436833.jpeg')}}" width="100%"  alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h1 class="card-title">Резинки для волос</h1>
                                    <p>Резинка для волос это эластичное кольцо для собирания волос и создания прически.</p>
                                    <p>В основном резинка для волос предназначается для того, чтобы предотвратить попадание волос в глаза или механическую технику во время домашней работы.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Виды резинок</h2>
                            <div class="card-text">
                                <p>На нашем сайте вы можете выбрать и заказать резинки скранч. Это широкая резинка из ткани для волос, популярная в 1980-х, начале 1990-х, а затем и в 2010-х годах</p>
                                <p>Посмотрите на образцы моих изделий ручной работы</p>
                            </div>
                        </div>
                    </div>
                    <div class="owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                @foreach($data['bant'] as $item)
                                    <div class="owl-item">

                                        <h5 style="height: 75px;">{{$item['name']}}</h5>
                                        <img src="{{$item['images'][0]['file_name']}}" class="img-fluid">

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                <section class="container-md">
                    <article>
                        <h2>Чокер</h2>
                        <p>Чокер — короткое ожерелье, которое плотно прилегает к шее, оснащено регуляторами размера.</p>
                        <p>Такое украшение имеет множество разновидностей. Чокеры изготавливаются из ракушек, дерева, кости, драгоценных металлов, драгоценных и полудрагоценных камней, кожи, пластмассы, бархата, атласа и тому подобное.</p>
                        <p>В моем магазине чокеры сделаны из бисера, на прочном ювелирном тросике</p>

                    </article>
                    <div class="owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                @foreach($data['chocker'] as $item)
                                    @if($item['attributes'][6]['values'][0]['value'] === 'синий')
                                    <div class="owl-item">

                                        <h5>{{$item['name']}}</h5>
                                        <img src="{{$item['images'][0]['file_name']}}" class="img-fluid">

                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </section>
        </div>
    </body>
</html>
