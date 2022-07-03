<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="description">
        <title>1</title>

        <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}"/>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
    </head>
    <body class="container-fluid">
        <header >

        </header>

        <section class="container-md">
            @php($n = 0)
            @foreach($items as $item)

                @if($n % 3 == 0)
                <div class="row">
                    @endif
                    <div class="card col-sm">
                        <div class="card-body">
                            <div class="card-title">
                                <h2>{{$item['name']}} {{$n % 3}}</h2>
                            </div>
                            <div class="card-img">
                                <img src="{{$item['primary_image']}}" class="img-fluid">
                            </div>
                            <div class="card-footer">
                                <p><span>Цена с учетом скидок: </span>{{$item['marketing_price']}}</p>
                            </div>
                        </div>
                    </div>

                    @php($n++)
                    @if($n % 3 == 0)
                        </div>
                @endif
            @endforeach
        </section>


        </section>
    </body>
</html>
