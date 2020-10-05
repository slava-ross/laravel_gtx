@extends('layouts.layout', ['title' => 'Редактирование отзыва'])
@section('content')
    <form action="{{ route('comment.update', ['id'=>$comment->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <h3>Редактировать отзыв</h3>
        @include('comments.parts.form')
        <input type="submit" value="Редактировать отзыв" class="btn btn-outline-success">
    </form>
@endsection
