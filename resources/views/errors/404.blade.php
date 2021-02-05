@extends('layouts.layout', ['title' => 'Ошибка 404'])
@section('content')
    <div class="card">
        <h1 class="card-header">Здесь нет такой страницы! (Ошибка 404)</h1>
        <img src="{{ asset('images/404.jpg') }}" alt="Ошибка 404" class="p404">
        <a href="/" class="btn btn-secondary ">Вернуться на Главную</a>
    </div>
@endsection
