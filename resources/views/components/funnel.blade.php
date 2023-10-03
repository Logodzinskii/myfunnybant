<div class="container sticky-top bg-light mt-3 pt-3 mb-3 pb-3 shadow">
    <div class="dropdown sticky-top">
        <form class="d-flex" method="post" action="{{route('find')}}">
            @csrf
            <input name="funnel" class="form-control me-2" type="search" placeholder="Напишите запрос. Например Розовый, или единорог, или зефирка" aria-label="Search" required>
            <button class="my-button btn btn-sm g-2 h-4 m-1 text-white" type="submit">
        <i class="bi-search"></i></button>
            <a href="{{url('user/view/cart')}}" class="my-button btn btn-sm g-2 h-4 m-1 text-white" style="width:200px;font-size: 1.2em">
                <i class="bi-cart4"></i>
                <span id="total"></span> шт.
                <span id="totalSum"></span>Р.
            </a>
        </form>
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    </div>
</div>

