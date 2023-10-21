@extends('layouts.app')
@section('content')
    <div class="container">
    @if (session('result'))
        <div class="alert alert-success">
            <h2>{{ session('result') }}</h2>
        </div>
    @elseif (session('denied'))
        <form method="post" action="{{url('/user/confirm/code')}}">
            @csrf
            <input type="number" name="code" required>
            <button type="submit" style="color: #6f42c1">Отправить</button>
        </form>
         <div class="alert alert-danger">
            <h2>{{ session('denied') }}</h2>
         </div>
        @else

            <h1 style="color: #6f42c1"><b>Здравствуйте</b></h1>
            <p>Мы рады сообщить Вам о том, что Ваш заказ, успешно оформлен.</p>
            <h2>Для подтверждения заказа, на электронную почту направлена ссылка.</h2>
            <p>Зайдите в вашу электронную почту  и перейдите по ссылке для подтверждения заказа.</p>
            <h2>Пожалуйста, для подтверждения заказа, введите код который мы вам отправили на электронную почту</h2>
            <form method="post" action="{{url('/user/confirm/code')}}">
                @csrf
                <input type="number" name="code" required>
                <button type="submit" style="color: #6f42c1">Отправить</button>
            </form>
    @endif
        @foreach($errors->all() as $error)
            <div class="alert alert-danger ">{{ $error }}</div>
        @endforeach
        </div>
@endsection
