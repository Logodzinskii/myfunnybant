<div class="container sticky-top bg-light mt-3 pt-3 mb-3 pb-3 shadow">
    <div class="dropdown sticky-top">
        <form class="d-flex" method="post" action="{{route('find')}}">
            @csrf
            <input name="funnel" class="form-control me-2" type="search" placeholder="Напишите запрос. Например Розовый, или единорог, или зефирка" aria-label="Search" required>
            <button class="my-button btn btn-sm g-2 h-4 m-1 text-white" type="submit">
        <i class="bi-search"></i></button>
            <a href="{{url('user/view/cart')}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white" style="width:200px;font-size: 1.2em">
                <i class="bi-cart4"></i>
                <span class="total"></span> шт.
                <span class="totalSum"></span>Р.
            </a>
        </form>
    </div>
    <p>
        <a class="btn rounded-2 text-light m-2 p-2" style="background-color: #6610f2; size: 2em" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Фильтры
        </a>
    </p>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <div class="dropdown sticky-top d-flex justify-content-start flex-wrap">
                <div class="nav-item dropdown m-3">
                    <a class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-funnel-fill rounded-2 text-light m-2 p-2" style="background-color: #6610f2; size: 2em"></i>По категории
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(\Illuminate\Support\Facades\DB::table('ozon_shop_items')->orderBy('created_at', 'desc')->groupBy('category')->get() as $category)
                            <li>
                                <a class="dropdown-item" href="{{url('/filter/'.$category->category)}}">{{$category->type}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="nav-item dropdown m-3">
                    <a class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-funnel-fill rounded-2 text-light m-2 p-2" style="background-color: #6610f2; size: 2em"></i>По цвету
                    </a>
                    <ul class="dropdown-menu overflow-scroll" style="height: 80vh">
                        @php
                            $newArr = [];
                        @endphp
                        @foreach(\Illuminate\Support\Facades\DB::table('ozon_shop_items')->orderBy('colors', 'desc')->groupBy('colors')->get() as $colors)
                            @php
                                $arr = json_decode($colors->colors,true);

                                        foreach ($arr as $arrString)
                                            {
                                                $string = explode(' ', $arrString);
                                                $newArr[] = implode(' ', $string);
                                            }
                            @endphp
                        @endforeach
                        @foreach(array_unique($newArr) as $color)
                            <li>
                                <a class="dropdown-item" href="{{url('/filter/colors/'.$color)}}">{{$color}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="nav-item dropdown m-3">
                    <a class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-funnel-fill rounded-2 text-light m-2 p-2" style="background-color: #6610f2; size: 2em"></i>По материалу
                    </a>
                    <ul class="dropdown-menu overflow-scroll" style="height: 80vh">
                        @php
                            $newArr = [];
                        @endphp
                        @foreach(\Illuminate\Support\Facades\DB::table('ozon_shop_items')->orderBy('material', 'desc')->groupBy('material')->get() as $colors)
                            @php
                                $arr = json_decode($colors->material,true);

                                        foreach ($arr as $arrString)
                                            {
                                                $string = explode(' ', $arrString);
                                                $newArr[] = implode(' ', $string);
                                            }
                            @endphp
                        @endforeach
                        @foreach(array_unique($newArr) as $color)
                            <li>
                                <a class="dropdown-item" href="{{url('/filter/material/'.$color)}}">{{$color}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="nav-item dropdown m-3">
                    <a class="nav-link dropdown-toggle " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-funnel-fill rounded-2 text-light m-2 p-2" style="background-color: #6610f2; size: 2em"></i>По цене
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{url('/filter/price/max')}}">Сначала дорогие</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{url('/filter/price/min')}}">Сначала недорогие</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

