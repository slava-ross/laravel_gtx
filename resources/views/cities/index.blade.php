@extends('layouts.layout', ['title' => 'Главная страница'])

@section('content')
    <div class="row">
        @foreach($cities as $city)
        <div class="col-6">
            <div class="card-body">
                <h2><a href="{{ asset(route('comment.index', ['city_chosen'=>$city->name])) }}" class="card-descr">{{ $city->name }}</a></h2>
            </div>
        </div>
        @endforeach
    </div>

@endsection
