<div class="form-group">
    <label for="title">Заголовок:</label>
    <input name="title" id="title" type="text" class="form-control" required value="{{ old('title') ?? $comment->title ?? '' }}">
</div>
<div class="form-group">
    <label for="comment_text">Текст отзыва:</label>
    <textarea name="comment_text" id="comment_text" rows="10" class="form-control" required>{{ old('comment_text') ?? $comment->comment_text ?? '' }}</textarea>
</div>
@if($new_comment)
    <div class="form-group">
        <label for="cities">Город:</label>
        <select size="5" name="cities[]" id="cities" class="form-control" multiple>
            @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
            @endforeach
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
