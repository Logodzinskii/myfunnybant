@extends('admin.layouts.adminHome')
@section('content')
    <div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Фото</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Итого</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($data))
                    @foreach($data[0] as $sales)
                    <tr>
                        <th scope="row">-</th>
                        <td>{{$sales->name}}</td>
                        @foreach($sales->images[0] as $image)
                            <td><img src="{{$image}}" class="img-thumbnail" style="height: 80px"/></td>
                        @break
                        @endforeach
                            <td>{{$sales->price}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

    </div>
@endsection
