@extends('admin.layouts.adminHome')
@section('content')
    <div class="container px-4 mx-auto">

        <div class="p-6 m-20 bg-white rounded shadow">
            {!! $chart->container() !!}
        </div>

        <script src="{{ $chart->cdn() }}"></script>

        {{ $chart->script() }}
    </div>
@endsection
