<?php
/**
 * @var \App\Models\Article $article
 */
?>

@extends('layouts.admin')

@section('title') {{ __('admin/article.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <form action="{{ route('admin.article.update',['article'=>$article->id]) }}" method="post"  enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-8">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $article->title }}">
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ $article->slug }}">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" rows="10" class="form-control">{{ $article->content }}</textarea>
                </div>
            </div>
            <div class="col-4 widgets-area">
                @include('admin.article.widgets.submit',['article'=>$article])
            </div>
        </div>
    </form>
@endsection
