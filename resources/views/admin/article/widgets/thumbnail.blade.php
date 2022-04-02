<?php
/**
 * @var \App\Models\Article $article
 */
?>
<div class="card p-3" id="thumbnail">
    <div class="mb-3 {{ ($article?->thumbnail_path) ?: 'd-none' }}">
        <img src="{{ asset($article?->thumbnail_path) }}" alt="" class="img-fluid">
    </div>
    <label class="set {{ ($article?->thumbnail_path) ? 'd-none' : '' }}">Set Thumbnail
        <input type="file" name="thumbnail" class="d-none" accept="image/*">
    </label>
    <a href="#" class="remove {{ ($article?->thumbnail_path) ? '' : 'd-none' }}">Remove Thumbnail</a>
    <input type="hidden" name="thumbnail_path" value="{{ $article?->thumbnail_path }}">
</div>
