<?php
/**
 * @var \App\Models\Article $article
 * @var \Laravel\Sanctum\NewAccessToken $token
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

@section('js')
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor.create( document.querySelector( '#content' ), {
                simpleUpload: {
                    // The URL that the images are uploaded to.
                    uploadUrl: '{{ route('admin.upload.image') }}',

                    // Headers sent along with the XMLHttpRequest to the upload server.
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        Authorization: 'Bearer {{ $token }}'
                    }
                }
            }).catch( error => {
                console.error( error );
            } );
    </script>
@endsection
