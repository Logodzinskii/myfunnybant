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
    <script type="text/javascript">
        $(document).ready(function(){
            alert('asd');
            $(".owl-carousel").owlCarousel(

            );
        });
    </script>

</head>
<body class="container-fluid">
        <header >

        </header>
        <div class="owl-carousel owl-theme">
            <div style="width: 200px; height: 200px; background-color: #0a53be"> Пример 1</div>
            <div style="width: 200px; height: 200px; background-color: #0a53be"> Пример 2</div>
            <div style="width: 200px; height: 200px; background-color: #0a53be"> Пример 3</div>
            <div style="width: 200px; height: 200px; background-color: #0a53be"> Пример 4</div>
        </div>
    </body>
</html>
