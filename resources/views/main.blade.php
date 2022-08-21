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
        <header >
            @include('header')
        </header>
        <section class="container-md">
            <article>
                <h1>Аксессуары для волос ручной работы - myfunnybant </h1>
                <h2>Резинки для волос</h2>
                <p>Резинка для волос это эластичное кольцо для собирания волос и создания прически.</p>
                <p>В основном резинка для волос предназначается для того, чтобы предотвратить попадание волос в глаза или механическую технику во время домашней работы.</p>
                <p>Сегодня этот аксессуар используется для создания причесок, таких как: <a href="#">конский хвост</a>, <a href="#">коса</a> </p>
            <h2>Виды резинок</h2>
                <p>На нашем сайте вы можете выбрать и заказать резинки скранч. Это широкая резинка из ткани для волос, популярная в 1980-х, начале 19090-х, а затем и в 2010-х годах</p>
                <p>Посмотрите на образцы моих изделий ручной работы</p>
                <h2>Скранч</h2>
            </article>
            <div class="owl-carousel owl-theme owl-loaded">
                <div class="owl-stage-outer">
                    <div class="owl-stage">
                            <div class="owl-item">
                                <h2>item 1</h2>
                                <img src="{{asset('images/logo/logo.png')}}" class="img-fluid">
                            </div>
                            <div class="owl-item">
                                <h2>item 2</h2>
                                <img src="{{asset('images/logo/logo.png')}}" class="img-fluid">
                            </div>
                        <div class="owl-item">
                            <h2>item 3</h2>
                            <img src="{{asset('images/logo/logo.png')}}" class="img-fluid">
                        </div>
                        <div class="owl-item">
                            <h2>item 4</h2>
                            <img src="{{asset('images/logo/logo.png')}}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>

        </section>
    </body>
</html>
