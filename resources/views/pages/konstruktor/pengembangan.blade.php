@extends('layouts.konstruktor.adminbsb')

@section('content')

@if (session('status'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<pengembangan-konstruktor-component :data-field-penilaian="{{$fieldPenilaian}}" :data-pengembangan="{{$pengembangan}}"></pengembangan-konstruktor-component>
@endsection


