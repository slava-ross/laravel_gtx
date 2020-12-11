@extends('layouts.layout', ['title' => 'Главная страница'])
@section('content')
        @if(empty($city_name))
            @if(!empty($cities))
                <ul class="list-group">
                    @foreach($cities as $city)
                        <li class="list-group-item list-group-item-info"><a href="{{ asset(route('comment.index', ['city_id'=>$city->id, 'city_name'=>$city->name])) }}" class="card-descr">{{ $city->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-danger alert-dismissible show flash" role="alert">
                    Нет городов с отзывами
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endif
@endsection

{{-- Модальное окно выбора города --}}
@section('modal')
    @if(!empty($city_name))
        <div id="cityModalBox" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        Ваш город: <span>{{ $city_name }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="city-confirm" class="btn btn-primary" data-dismiss="modal">Да</button>
                        <button type="button" id="city-another" class="btn btn-secondary">Выбрать другой</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
