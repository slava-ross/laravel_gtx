@extends('layouts.layout', ['title' => 'Создание нового отзыва'])
@section('content')
    <form action="{{ route('comment.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Новый отзыв</h3>
        @include('comments.parts.form')
        <input type="submit" value="Создать отзыв" class="btn btn-outline-success">
    </form>
@endsection
