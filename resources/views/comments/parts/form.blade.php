<div class="form-group">
    <label for="title">Заголовок:</label>
    <input name="title" id="title" type="text" class="form-control" required value="{{ old('title') ?? $comment->title ?? '' }}">
</div>
<div class="form-group">
    <label for="comment_text">Текст отзыва:</label>
    <textarea name="comment_text" id="comment_text" rows="10" class="form-control" required>{{ old('comment_text') ?? $comment->comment_text ?? '' }}</textarea>
</div>
@if($new_comment)
    <div class="form-group city-holder">
        <ul id="city-shell" class="city-shell"></ul>
    </div>
    <div class="form-group">
        <label for="cities-data">Выберите один или несколько городов (Нет выбора - все города):</label>
        <input id="cities-data" class="city-multiple form-control" type="search" placeholder="Начните писать ваш город ...">
        <select id="city-select" class="city-select" name="cities[]" multiple hidden>
        </select>
</div>
@endif
<div class="form-group">
<label for="rating">Рейтинг (1-5):</label>
<input name="rating" id="rating" type="number" min="1" max="5" class="form-control" required value="{{ old('rating') ?? $comment->rating ?? '' }}">
</div>
<div class="form-group">
<label for="image">Прикрепите изображение (по желанию):</label><br>
<input name="img" id="image" type="file">
</div>



@extends('layouts.layout', ['title' => 'Создание нового отзыва'])
@section('content')
    <form action="{{ route('comment.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Новый отзыв</h3>
        @include('comments.parts.form')
        <input type="submit" value="Создать отзыв" class="btn btn-outline-success">
    </form>
@endsection

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


{{-- Модальное окно создания и редактирования отзывов --}}

<!-- button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button -->

<div class="modal fade" id="create-comment-modal" tabindex="-1" role="dialog" aria-labelledby="createCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-comment-modal-label">Новый отзыв</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="create-comment-modal-body">
                {{-- Заголовок отзыва --}}
                <div class="form-group">
                    <label for="title">Заголовок:</label>
                    <input name="title" id="title" type="text" class="form-control" required">
                </div>
                {{-- Текст отзыва --}}
                <div class="form-group">
                    <label for="comment_text">Текст отзыва:</label>
                    <textarea name="comment_text" id="comment_text" rows="10" class="form-control" required></textarea>
                </div>
                {{-- Города/города отзыва --}}
                <div class="form-group city-holder">
                    <ul id="city-shell" class="city-shell"></ul>
                </div>
                <div class="form-group">
                    <label for="cities-data">Выберите один или несколько городов (Нет выбора - все города):</label>
                    <input id="cities-data" class="city-multiple form-control" type="search" placeholder="Начните писать ваш город ...">
                    <select id="city-select" class="city-select" name="cities[]" multiple hidden>
                    </select>
                </div>
                {{-- Рейтинг --}}
                <div class="form-group">
                    <label for="rating">Рейтинг (1-5):</label>
                    <input name="rating" id="rating" type="number" min="1" max="5" class="form-control" required">
                </div>
                {{-- Изображение --}}
                <div class="form-group">
                    <label for="image">Прикрепите изображение (по желанию):</label><br>
                    <input name="img" id="image" type="file">
                </div>
            </div>
            <div class="modal-footer">
                @csrf
                <button type="button" id="new-comment-create" class="btn btn-outline-success">Создать отзыв</button>
            </div>
        </div>
    </div>
</div>
