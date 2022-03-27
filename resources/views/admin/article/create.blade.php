@extends('layouts.admin')

@section('title') {{ __('admin/article.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <form action="{{ route('admin.article.store') }}" method="post"  enctype="multipart/form-data">
        <div class="row">
            <div class="col-8">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" rows="10" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="meta-content" class="form-label">Meta-Content</label>
                    <textarea name="meta_content" id="meta-content" rows="10" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Less then 10, Comma separated">
                </div>
            </div>
            <div class="col-4 widgets-area">
                @include('admin.article.widgets.submit',['article'=>[]])
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
                    Authorization: 'Bearer {{ $token->plainTextToken }}'
                }
            }
        }).catch( error => {
            console.error( error );
        } );
    </script>
@endsection
