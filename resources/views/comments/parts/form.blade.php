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
