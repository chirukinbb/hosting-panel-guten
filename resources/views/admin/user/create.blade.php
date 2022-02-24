@extends('layouts.admin')

@section('title') {{ __('admin/user.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <form action="{{ route('admin.user.store') }}" method="post"  enctype="multipart/form-data">
        <div class="row">
            <div class="col-8">
                @csrf
                <div class="mb-3">
                    <label for="avatar" class="form-label">Avatar</label>
                    <input type="file" class="form-control" id="avatar" name="avatar">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="biography" class="form-label">Biography</label>
                    <textarea name="biography" id="biography" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-4 widgets-area">
                @include('admin.user.widgets.submit',compact('roles'))
            </div>
        </div>
    </form>
@endsection
