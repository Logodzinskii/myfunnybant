<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: '/counter/',
            success: function(res, status, xhr) {
                $('.count-offer').text(res);
            }
        });
        $.post('{{route('get.total')}}',
            {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                id: 0
            },
            function (data) {
                total();
            });
        $('.like').on('click', function () {
            var id = $(this).data('heart');
            var span = $(this);
            $.post('/addlike',
                {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                function (data) {
                    span.parent().find('span').text(data);
                    span.removeClass('bi');
                    span.removeClass('like');
                    span.addClass('bi-like');
                    let s = "<?php if (session()->has('ozon_id')) {
                        echo count(session()->get('ozon_id'));
                    } else {
                        echo 0;
                    } ?>";
                    $('#countLike').text(s);
                });
        });
        $('.add-cart').on('click', function () {
            let id = $(this).data('add-cart');

            $('body').css('cursor', 'progress');
            $.post('{{route('add.cart')}}',
                {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    ozon_id: id
                },
                function (data) {
                    if(data == 0){
                       window.location.replace('/home');
                    }else{
                        $('body').css('cursor', 'default');
                        total();
                    }
                });
        });
        $('.like').on('click', function () {
            var id = $(this).data('heart');
            var span = $(this);
            $.post('/addlike', {"_token": $('meta[name="csrf-token"]').attr('content'), id: id}, function (data) {

                span.parent().find('span').text(data);
                span.removeClass('bi');
                span.removeClass('like');
                span.addClass('bi-like');
                let s = "<?php if (session()->has('ozon_id')) {
                    echo count(session()->get('ozon_id'));
                } else {
                    echo 0;
                } ?>";
                $('#countLike').text(s);
            });
        })

        $('.update').on('mousemove', function () {
            $('body').css('cursor', 'pointer')
        });
        $('.update').on('mouseleave', function () {
            $('body').css('cursor', 'default')
        });

        $('.plus').on('click', function () {
            let idd = $(this).data("id");
            let val = 1;
            update(val, idd);
        })
        $('.minus').on('click', function () {
            let idd = $(this).data("id");
            let val = -1;
            update(val, idd);
        })

        function total() {
            $('body').css('cursor', 'progress');
            $.post('{{route('get.total')}}',
                {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    id: 0
                },
                function (data) {
                    $('.total').text(data[1] + ' шт.');
                    $('.totalSum').text(data[0] + 'Р')
                    $('body').css('cursor', 'default');

                    var roundLogEl = document.querySelector('.total');
                    var roundLogElSum = document.querySelector('.totalSum');
                    anime({
                        targets: roundLogEl,
                        innerHTML: [0, data[1]],
                        easing: 'linear',
                        round: 1 // Will round the animated value to 1 decimal
                    });
                    anime({
                        targets: roundLogElSum,
                        innerHTML: [0, data[0]],
                        easing: 'linear',
                        round: 10 // Will round the animated value to 1 decimal
                    });
                    anime.set(roundLogEl, {
                        translateX: function() { return anime.random(50, 250); },
                        rotate: function() { return anime.random(0, 360); },
                    });

                });
        }

        function update(val, idd) {
            $.post('/user/update/quantity', {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    id: idd,
                    quantity: val
                },
                function (data) {
                    let priceItem = $('body').find(`[data-price-item='` + idd + `']`).text();
                    $('body').find(`[data-idres='` + idd + `']`).text(data);
                    $('body').find(`[data-price='` + idd + `']`).text(data * parseInt(priceItem));
                    $('input[name=newozon]').val(data);
                    total();
                });
        }

        $('.delete-item-cart').on('click', function () {
            $.post('/user/delete/cart', {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    id: $(this).data("delete")
                },
                function (data) {
                    console.log(data);
                    $('body').find(`[data-delete='` + data.id + `']`).parent().parent().remove();
                    if(data.count === 0)
                    {
                        window.location.replace('/');
                    }
                    total();
                });
        })
    })
</script>
<nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
    <div class="container">
        <div class="col-2">
            <img src="{{asset('/images/logo/logo.png')}}" class="img-fluid" style="width: 8vw">
        </div>
        <a class="navbar-brand fs-1 text-uppercase staggering-easing-demo d-flex flex-wrap" style="color: saddlebrown" href="{{ url('/') }}">
            <div class="el" >M</div>
            <div class="el" >Y</div>
            <div class="el" >F</div>
            <div class="el" >U</div>
            <div class="el" >N</div>
            <div class="el" >N</div>
            <div class="el" >Y</div>
            <div class="el" >B</div>
            <div class="el" >A</div>
            <div class="el" >N</div>
            <div class="el" >T</div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                @if(isset(Auth::user()->role) &&  Auth::user()->role === 1)
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Управление сайтом</a>
                    </li>
                @endif
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Выйти') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
