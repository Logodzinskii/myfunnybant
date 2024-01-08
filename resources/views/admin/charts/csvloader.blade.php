@extends('admin.layouts.adminHome')
@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 70vh">
        <form action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <input type="file" name="file" class="form-control" accept=".csv" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success">Загрузить</button>
                </div>
            </div>
        </form>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endsection
