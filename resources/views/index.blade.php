<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="description" CONTENT="{{$meta}}">
        <title>{{$webTitle}}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}"/>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
    </head>
    <body class="container-fluid">
        <header >

        </header>
        <section class="container-md">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <img src="{{asset('images/logo/img.png')}}" alt="myfunnybant" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 card">
                    <h1>Аксессуары для волос ручной работы</h1>
                    <p>Сайт находится в стадии разработки, скоро мы все настроим!</p>
                    <p>Выбрать товар и сделать заказ вы можете в моем магазине на <a href="https://www.ozon.ru/seller/myfunnybant-302542/">Ozon</a></p>
                </div>
            </div>
        </section>


        </section>
    </body>
</html>
