@extends('layouts.layout', ['title' => 'Главная страница'])

@section('content')
    <!--div class="row"-->
    <ul class="list-group">
        @foreach($cities as $city)
            <li class="list-group-item list-group-item-info"><a href="{{ asset(route('comment.index', ['city_chosen'=>$city->id])) }}" class="card-descr">{{ $city->name }}</a></li>
        @endforeach
    </ul>
@endsection

{{-- Модальное окно выбора города --}}
@section('modal');
<div id="cityModalBox" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                Ваш город: <span>{{ $city_name }}</span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Да</button>
                <button type="button" class="btn btn-secondary">Выбрать другой</button>
            </div>
        </div>
    </div>
</div>
@endsection
