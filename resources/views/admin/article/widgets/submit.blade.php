<?php
/**
 * @var \App\Models\Article $article
 */
?>
<div class="card p-3">
    <div class="mb-3">
        <label for="save_to" class="form-label">{{ __('admin/article.to') }}</label>
        <select class="form-control" id="save_to" name="save_to">
            @foreach(\App\Models\Article::$statuses as $key => $status)
                <option value="{{ $key }}" {{ (is_a($article,\App\Models\Article::class) && __($status) === $article->getStatus()) ? 'selected' : '' }}>{{ __($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-control" id="type" name="type">
            @foreach(\App\Models\Article::$types as $key => $type)
                <option value="{{ $key }}" {{ (is_a($article,\App\Models\Article::class) && $type === $article->type) ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('admin/article.submit.button') }}</button>
</div>
