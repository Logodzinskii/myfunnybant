<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="description">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
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
            $(".owl-carousel:eq(0)").owlCarousel(
                {
                    autoWidth: false,
                    dots: false,
                    margin:10,
                    autoplay: true,
                    responsive:{
                        0:{
                            items:3
                        },
                        600:{
                            items:3
                        },
                        1000:{
                            items:3
                        }
                    }
                }
            );
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
    <body class="container-fluid p-0 m-0">
        @include('header')

    <section style="min-height: 100vh" class="p-0 m-0">
        <div class="card mb-3 h-100 p-0 m-0">
            <h1 class="card-title text-center">Аксессуары для волос ручной работы</h1>
            <div class="row g-0 p-0 m-0">
                <div class="col-md-4">
                    <img src="{{asset('images/logo/logo.png')}}" width="100%"  alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h1 class="card-title">В моем магазине вы можете выбрать и заказать</h1>

                            <div class="owl-carousel owl-theme owl-loaded side">
                                <div class="owl-stage-outer">
                                    <div class="owl-stage">
                                    @foreach($data['category']['type'] as $type => $count)
                                        <a href="#{{$type}}" class="owl-item btn btn-outline-secondary" style="background-color: white">
                                            <p style="font-size: small">{{$type}}</p>
                                            <p>{{$count}} шт.</p>
                                        </a>
                                    @endforeach
                                    </div>
                                </div>
                            </div>

                        <p>Все работы, представленные в моем магазине сделаны с любовью</p>
                        <p>Я использую только проверенные мной материалы</p>
                        <p>Мои работы высокого качества и служат долго</p>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="card-title text-center">Посмотрите мои работы</h1>
    </section>
        <div class="row p-0 m-0">
            <section>
                <!-- Button trigger modal
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Фильтр по цвету
                </button>-->

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Фильтр по цвету</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @foreach($data['category']['colors'] as $color => $count)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{$color}}" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            {{$color}} - {{$count}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                <button type="button" class="btn btn-primary">Показать</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-lg-12">
                    @foreach($data['bant'] as $items)
                        <div class="container">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">
                                @foreach($items as $item)
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header" style="min-height: 120px">
                                                <h5>{{$item['name']}}</h5>
                                            </div>
                                            <div class="card-body" >
                                                <div class="owl-carousel owl-theme owl-loaded side">
                                                    <div class="owl-stage-outer">
                                                        <div class="owl-stage">
                                                            @foreach($item['images'] as $image)
                                                                <div class="owl-item img">
                                                                    <img src="{{$image['file_name']}}" width="200" class="img-fluid">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer" style="min-height: 120px">
                                                @foreach($item['attributes'] as $attribute)
                                                    @if ($attribute['attribute_id'] == 10096)
                                                        <p>Цвет: {{$attribute['values'][0]['value']}}</p>
                                                        <form id="itemInfo" method="post" action="{{route('shop.information')}}">
                                                            @csrf
                                                            <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                                            <input type="hidden" name="id" value="{{$item['id']}}">
                                                            <!--<button type="submit" class="btn btn-outline-secondary">Подробнее</button>-->
                                                        <a href="/category/{{$item['id']}}" class="btn btn-sm btn-outline-dark">Подробнее</a>
                                                        </form>
                                                        <a href="https://www.ozon.ru/seller/myfunnybant-302542/aksessuary-7697/?miniapp=seller_302542&text={{$item['name']}}' '{{$attribute['values'][0]['value']}}" class="btn btn-sm btn-outline-secondary">Перейти в Ozon</a>
                                                    @endif
                                                    @if ($attribute['attribute_id'] == 8229)
                                                        <p>Тип: {{$attribute['values'][0]['value']}}</p>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
            </section>
        </div>
    @include('footer')
    </body>
</html>
