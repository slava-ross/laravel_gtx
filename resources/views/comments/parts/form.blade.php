<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="comment-modal-label">{{ $modal_title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" id="comment-modal-body">
        {{-- Заголовок отзыва --}}
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input name="title" id="title" type="text" class="form-control" required" value="{{ $comment->title ?? '' }}">
        </div>
        {{-- Текст отзыва --}}
        <div class="form-group">
            <label for="comment_text">Текст отзыва:</label>
            <textarea name="comment_text" id="comment_text" rows="10" class="form-control" required>{{ $comment->comment_text ?? '' }}</textarea>
        </div>
        {{-- Города/города отзыва --}}
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
        {{-- Рейтинг --}}
        <div class="form-group">
            <label for="rating">Рейтинг (1-5):</label>
            <input name="rating" id="rating" type="number" min="1" max="5" class="form-control" required" value="{{ $comment->rating ?? '' }}">
        </div>
        {{-- Изображение --}}
        <div class="form-group">
            <label for="image">Прикрепите изображение (по желанию):</label><br>
            <input name="img" id="image" type="file">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="{{ $button_id }}" class="btn btn-outline-success">{{ $button_text }}</button>
    </div>
</div>
