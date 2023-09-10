 @extends('layouts.app')
 @section('content')
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-md-2 g-2">
                    <div class="col card">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($res->images as $i=>$image)
                                    @if($i == 0)
                                <div class="carousel-item active">
                                    @else
                                        <div class="carousel-item ">
                                    @endif
                                    <img src="{{$image['file_name']}}" class="d-block w-100 rounded-1" alt="...">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Предыдущий</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Следующий</span>
                            </button>
                        </div>
                    </div>
                    <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h1>{{ $res->name }}</h1>
                            </div>
                            <div>
                                <ul class="d-flex flex-wrap justify-content-between p-0 w-0">
                                @foreach($res->attributes as $key=>$attribute)
                                    <li class="card p-3 text-center"><span class="badge bg-secondary">{{ $key }}</span>{{ strip_tags($attribute) }}</li>
                                @endforeach
                                    <li>Номер: {{$res->colors}}</li>
                                </ul>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
            </div>
 @endsection
