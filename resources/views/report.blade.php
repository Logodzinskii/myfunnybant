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
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card col-3">
                        <div class="card-body">
                            <div class="card-title">
                                <h2>Наименование товара</h2>
                            </div>
                            <div class="card-img">
                                <img src="{{asset('images/shop_images/file_29.jpg')}}" class="img-fluid">
                            </div>
                            <div class="card-footer">
                                <p>подвал</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>


        </section>
    </body>
</html>
