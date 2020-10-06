@extends('layouts.layout', ['title' => 'Ошибка 404'])
@section('content')
    <div class="card">
        <h2 class="card-header">Страница не существует!</h2>
        <img src="{{ asset('images/404.jpg') }}" alt="Ошибка 404" class="p404">
        <a href="/" class="btn btn-outline-primary ">Вернуться на Главную</a>
    </div>
@endsection
