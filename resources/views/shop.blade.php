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

        <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>

        <script src={{ asset('js/owl.carousel.min.js')}}></script>
    <script type="javascript">
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel(

            );
        });
    </script>

    </head>
    <body class="container-fluid">
        <header >

        </header>

        <section class="container-md">
            <div class="owl-carousel owl-theme owl-loaded">
                <div class="owl-stage-outer">
                    <div class="owl-stage">
                        @php($n = 0)
                        @foreach($data['items'] as $item)

                            @if($n % 3 == 0)
                                <div class="owl-item">
                                    @endif
                                    <div class="card col-sm">
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h2>{{$item['name']}}</h2>
                                            </div>
                                            <div class="card-img">
                                                <img src="{{$item['images'][0]['file_name']}}" class="img-fluid">
                                            </div>
                                            <div class="card-footer">
                                                <p><span>Цена с учетом скидок: </span></p>
                                                <p></p>

                                            </div>
                                        </div>
                                    </div>

                                    @php($n++)
                                    @if($n % 3 == 0)
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
                <div class="owl-nav">
                    <div class="owl-prev">prev</div>
                    <div class="owl-next">next</div>
                </div>
                <div class="owl-dots">
                    <div class="owl-dot active"><span></span></div>
                    <div class="owl-dot"><span></span></div>
                    <div class="owl-dot"><span></span></div>
                </div>
            </div>
            @php($n = 0)
            @foreach($data['items'] as $item)

                @if($n % 3 == 0)
                <div class="row">
                    @endif
                    <div class="card col-sm">
                        <div class="card-body">
                            <div class="card-title">
                                <h2>{{$item['name']}}</h2>
                            </div>
                            <div class="card-img">
                                <img src="{{$item['images'][0]['file_name']}}" class="img-fluid">
                            </div>
                            <div class="card-footer">
                                <p><span>Цена с учетом скидок: </span></p>
                                <p></p>

                            </div>
                        </div>
                    </div>

                    @php($n++)
                    @if($n % 3 == 0)
                        </div>
                @endif
            @endforeach
        </section>
        <p> <a href="/public/shop/{{$data['last_id']}}" >Назад</a>
            <a href="/public/shop/{{$data['last_id']}}" >Далее</a></p>

        </section>

    </body>
</html>
