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
                <div class="mb-3">
                    <label for="meta-content" class="form-label">Meta-Content</label>
                    <textarea name="meta_content" id="meta-content" rows="10" class="form-control">{{ $article->meta_content }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input type="text" class="form-control" id="keywords" value="{{ $article->keywords }}" name="keywords" placeholder="Less then 10, Comma separated">
                </div>
            </div>
            <div class="col-4 widgets-area">
                @include('admin.article.widgets.submit',['article'=>$article])
                @include('admin.article.widgets.thumbnail',['article'=>$article])
                @include('admin.article.widgets.announce')
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script>
        ClassicEditor.create( document.querySelector( '#content' ), {
                simpleUpload: {
                    // The URL that the images are uploaded to.
                    uploadUrl: '{{ route('admin.upload.image') }}',

                    // Headers sent along with the XMLHttpRequest to the upload server.
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        Authorization: 'Bearer {{ $token->plainTextToken }}'
                    }
                }
            }).catch( error => {
                console.error( error );
            } );

        $('#thumbnail .remove').on('click',function (e) {
            e.preventDefault()

            $('#thumbnail div, #thumbnail .remove').addClass('d-none')
            $('#thumbnail .set').removeClass('d-none')
            $('input[name=thumbnail_path]').val()
        })

        $('input[name=thumbnail]').on('change', function(event) {
            var reader = new FileReader()

            reader.onload = function(){
                $('#thumbnail img').attr('src',reader.result)
                $('#thumbnail div, #thumbnail .remove').removeClass('d-none')
                $('#thumbnail .set').addClass('d-none')
            }

            reader.readAsDataURL(event.target.files[0])
        })
    </script>
@endsection
