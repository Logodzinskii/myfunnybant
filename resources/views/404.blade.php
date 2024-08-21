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
    <script type="text/javascript">
        const countdown = document.querySelector('.countdown');
        const targetDate = new Date('2023-07-18T00:00:00');

        function updateCountdown() {
            const now = new Date();
            const remainingTime = targetDate - now;

            const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
            const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = days.toString().padStart(2, '0');
            document.getElementById('hours').innerText = hours.toString().padStart(2, '0');
            document.getElementById('minutes').innerText = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').innerText = seconds.toString().padStart(2, '0');
        }

        // Обновляем счетчик каждую секунду
        setInterval(updateCountdown, 1000);
    </script>

</head>
<body class="container-fluid">
<header >
    @include('header')
</header>
<section class="card d-flex flex-wrap">
    <h1 class="card-header">Аксессуары для волос ручной работы</h1>
    <div class="flex-wrap d-flex justify-content-center align-items-center">
        <div class="w-50">
            <p class="text-center">Сайт временно закрыт на технические работы</p>
            <p class="text-center">Скоро мы все настроим осталось ждать:</p>
            <div class="countdown d-flex flex-wrap h2 card-body justify-content-center align-items-center" style="color: #6f42c1">
                <div class="countdown-item w-100 text-center">
                    <span id="days"> 00 </span>
                    <span class="label"> д. </span>
                </div>
                <div class="countdown-item w-100 text-center">
                    <span id="hours"> 00 </span>
                    <span class="label"> час. </span>
                </div>
                <div class="countdown-item w-100 text-center">
                    <span id="minutes"> 00 </span>
                    <span class="label"> мин. </span>
                </div>
                <div class="countdown-item w-100 text-center">
                    <span id="seconds"> 00 </span>
                    <span class="label"> сек. </span>
                </div>
            </div>
        </div>
        <div class="w-50">
            <img src="{{asset('images/logo/logo.png')}}" class="img-fluid card-body">
        </div>
    </div>
</section>

</body>
</html>
