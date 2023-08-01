<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Myfunnybant - аксессуары для волос</title>
    <META NAME="description" content="Myfunnybant аксессуары для волос ручной работы. Купить бантики, банты, заколочки, чокеры в моем магазине.">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href={{ asset('css/owl.carousel.min.css') }}>
    <link rel="stylesheet" href={{ asset('css/owl.theme.default.min.css') }}>
    <link rel="stylesheet" href="{{asset('css/myfunnybant.css')}}">

    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
    <script src="{{asset('js/bootstrap.bundle.js')}}"></script>
    <script src={{ asset('js/owl.carousel.min.js')}}></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(84655657, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/84655657" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SVJMVRECBB"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SVJMVRECBB');
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(window).scroll(function() {

                if($(this).scrollTop() != 0) {

                    $('#toTop').fadeIn();

                } else {

                    $('#toTop').fadeOut();

                }

            });

            $('#toTop').click(function() {

                $('body,html').animate({scrollTop:0},800);

            });
        });
    </script>
    <!-- Yandex.RTB -->
    <script>window.yaContextCb=window.yaContextCb||[]</script>
    <script src="https://yandex.ru/ads/system/context.js" async></script>
</head>
<body class="container-fluid p-0 m-0">
@include('header')
<section style="min-height: 100vh" class="p-0 m-0">
    <div class="card mb-3 h-100 p-0 m-0">
        <h1 class="card-title text-center">Аксессуары для волос ручной работы</h1>
        <div class="row g-0 p-0 m-0">
            <div class="col-md-4">
                <img src="{{asset('images/logo/logo.png')}}" width="100%"  alt="myfunnybant">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h1 class="card-title">
                        В моем магазине вы можете выбрать и заказать аксессуары для волос ручной работы
                    </h1>
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="row">

                            @foreach($data['category']['typeCode'] as $arr)
                                <a href="#{{$arr['category_id']}}" class="col-6 col-sm-6 btn fs-6" style="border-bottom: 2px solid #6f42c1;">
                                    <p>{{$arr['text_menu'][0]}} </p>
                                </a>
                            @endforeach

                        </div>
                    </div>
                <!--<p>Фильтр по цвету</p>
                            <div class="owl-carousel owl-theme owl-loaded side">
                                <div class="owl-stage-outer">
                                    <div class="owl-stage">
                                        @foreach($data['category']['colors'] as $color => $count)
                    <div class="owl-item" style="background-color: white; border: 1px solid #000000; border-radius: 5px; padding: 5px">
{{$color}} - {{$count}}
                        </div>
@endforeach
                    </div>
                </div>
            </div>-->

                    <p>Все работы, представленные в моем магазине сделаны с любовью</p>
                    <p>Я использую только проверенные мной материалы</p>
                    <p>Мои работы высокого качества и служат долго</p>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="row p-0 m-0">
    <section>
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
        @foreach($data['bant'] as $key=>$items)
            <div class="container" style="margin-top: 40px; padding-top: 10px; border-top: 1px solid  #6f42c1">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3">
                    @foreach($items as $item)
                        <div class="col" id="{{$key}}">
                            <div class="card">
                                <div class="card-header" style="min-height: 120px">
                                    <h5>{{$item['name']}}</h5>
                                </div>
                                <div class="card-body side d-flex justify-content-center" >

                                    @for($i=0; $i<=0; $i++)
                                        <div class="img ">
                                            <img src="{{$item['images'][$i]['file_name']}}" alt="{{$item['name']}}" width="200" class="img-fluid">
                                        </div>
                                    @endfor

                                </div>
                                <div class="card-footer" style="min-height: 120px">
                                    @foreach($item['attributes'] as $attribute)
                                        @if ($attribute['attribute_id'] == 10096)
                                            <p>Цвет: {{$attribute['values'][0]['value']}}</p>
                                            <form id="itemInfo" method="post" action="{{route('seller.show', ['name'=> \Illuminate\Support\Str::slug($item['name'], '-')])}}">
                                                @csrf
                                                <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="id" value="{{$item['id']}}">
                                                <button type="submit" class="btn btn-outline-secondary">Подробнее</button>
                                                <!--<a href="{{route("seller.show", ['id'=>$item['id'], 'name'=>$item['name']])}}" class="btn btn-sm btn-outline-dark">Подробнее</a>-->
                                            </form>
                                            <a href="{{route("seller.ozon", ['url'=>$item['name'].$attribute['values'][0]['value']])}}" class="btn btn-sm btn-outline-secondary">Перейти в озон</a>

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
<div id="toTop" >^ Наверх</div>
<!-- Yandex.RTB R-A-2544919-1 -->
<script>window.yaContextCb.push(()=>{
        Ya.Context.AdvManager.render({
            "blockId": "R-A-2544919-1",
            "type": "floorAd"
        })
    })
</script>
@include('footer')
</body>
</html>
