@extends('admin.layouts.adminHome')
@section('content')
    <div class="container px-4 mx-auto">
        <form method="get" name="finance" action="{{url('/admin/finance/show')}}">
            @csrf
            <input type="date" name="dat"/>
            <button type="submit">Выбрать</button>
        </form>
        <div class="p-6 m-20 bg-white rounded shadow">
            {!! $chart->container() !!}
        </div>

        <script src="{{ $chart->cdn() }}"></script>

        {{ $chart->script() }}

        <div class="container d-flex justify-content-center flex-wrap">
            <div class="card">
                <div class="card-header">
                    OZON
                </div>
                <div class="card-body">
                    <h2>
                        {{$ozonYear}}
                    </h2>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Ярмарки
                </div>
                <div class="card-body">
                    <h2>
                        {{$salesYear}}
                    </h2>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Всего:
                </div>
                <div class="card-body">
                    <h2>
                        {{$ozonYear+$salesYear}}
                    </h2>
                </div>
            </div>
        </div>
    </div>

@endsection
