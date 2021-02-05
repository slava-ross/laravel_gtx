@extends('layouts.layout', ['title' => 'Главная страница'])
@section('content')
        @if(empty($city_name)) {{-- Не определили город --}}
            @if(!empty($cities)) {{-- Есть список городов с отзывами для отображения списка --}}
                <ul class="list-group">
                    @foreach($cities as $city)
                        <li class="list-group-item list-group-item-info"><a href="{{ asset(route('comment.index', ['city_id'=>$city->id])) }}" class="card-descr">{{ $city->name }}</a></li>
                    @endforeach
                </ul>
            @else {{-- Нет городов с отзывами --}}
                <div class="alert alert-danger alert-dismissible show flash" role="alert">
                    Нет городов с отзывами
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @else {{-- Определили город, вызвали модальное окно, форму с автокомплитом города и списком городов из базы (с отзывами в приоритете и без) рисуем скрытой --}}
            <div class="row city-choosing d-none">
                <form action="{{ url('/comment') }}" method="get">
                    @csrf
                    <div class="col-md-6">
                        <h1 class="form-group mb-3">Выбор города</h1>
                        <div class="form-group mb-3">
                            <input id="city" class="form-control city" type="search" name="city_name" placeholder="Начните писать ваш город ...">
                            <!-- input id="city" class="form-control city" type="text" name="city_name" placeholder="Начните писать ваш город ..." -->
                            <!-- input type="submit" value="Выбрать" class="btn btn-primary mt-3" -->
                        </div>
                    </div>
                </form>
                <ul class="list-group">
                    @foreach($cities as $city)
                        {{--, 'city_name'=>$city->name--}}
                        <li class="list-group-item list-group-item-info d-inline-flex justify-content-between"><a href="{{ asset(route('comment.index', ['city_id'=>$city->id])) }}" class="card-descr">{{ $city->name }}</a><span>Отзывов: {{ $city->cnt ? $city->cnt : '0' }}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif
@endsection

{{-- Модальное окно выбора города --}}
@section('modal')
    @if(!empty($city_name))
        <div id="cityModal" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        Ваш город: <span>{{ $city_name }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="city-confirm" class="btn btn-primary">Да</button>
                        <button type="button" id="city-another" class="btn btn-secondary" data-dismiss="modal">Выбрать другой</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
