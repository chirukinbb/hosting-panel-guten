<?php
/**
 * @var \App\Models\User $user
 */
?>

@extends('layouts.admin')

@section('title') {{ __('admin/article.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <h2>{{ $user->name }}({{ $user->email }})</h2>
    <form action="{{ route('admin.user.update',['user'=>$user->id]) }}" method="post"  enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-8">
                <div class="mb-3">
                    <label for="avatar" class="form-label">Avatar</label>
                    @if($user->data?->avatar_path)
                        <div class="mb-2 text-center">
                            <img src="{{ asset($user->data->avatar_path) }}" class="img-fluid" alt="">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="avatar" name="avatar">
                </div>
                <div class="mb-3">
                    <label for="public_name" class="form-label">Public Name</label>
                    <input type="text" class="form-control" id="public_name" name="public_name" value="{{ $user->data?->public_name }}">
                </div>
                <div class="mb-3">
                    <label for="biography" class="form-label">Biography</label>
                    <textarea name="biography" id="biography" rows="10" class="form-control">{{ $user->data?->biography }}</textarea>
                </div>
            </div>
            <div class="col-4 widgets-area">
                @include('admin.user.widgets.submit',compact(
                    'roles',
                    'user'
                ))
            </div>
        </div>
    </form>
@endsection
