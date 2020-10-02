@extends('layouts.layout')

@section('content')
    <div class="row">
        @foreach($cities as $city)
        <div class="col-6">
            <div class="card-body">
                <a href="{{ asset(route('comment.index', ['city_id'=>$city->id])) }}" class="card-descr">{{ $city->name }}</a>
            </div>
        </div>
        @endforeach
    </div>

@endsection
