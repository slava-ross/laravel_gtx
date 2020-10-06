@extends('layouts.layout', ['title' => "Редактирование отзыва №$comment->id"])
@section('content')
    <form action="{{ route('comment.update', ['comment'=>$comment->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <h3>Редактировать отзыв</h3>
        @include('comments.parts.form')
        <input type="submit" value="Редактировать отзыв" class="btn btn-outline-success">
    </form>
@endsection
