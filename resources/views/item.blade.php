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
                                            <img src="{{$image}}" class="img-fluid">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                    <div class="card">
                       <h1>Цена</h1>
                        <p>{{$res['marketing_price']}}</p>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>

        @include('footer')
    </body>
</html>
