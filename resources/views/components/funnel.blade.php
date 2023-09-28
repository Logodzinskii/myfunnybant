<div class="container sticky-top bg-light mt-3 pt-3 mb-3 pb-3 shadow">
    <div class="dropdown sticky-top">
        <form class="d-flex" method="post" action="{{route('find')}}">
            @csrf
            <input name="funnel" class="form-control me-2" type="search" placeholder="Напишите запрос. Например Розовый, или единорог, или зефирка" aria-label="Search" required>
            <button class="my-button btn btn-sm g-2 h-4 m-1 text-white" type="submit">
        <i class="bi-search"></i></button>
        </form>
        @error('funnel')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

