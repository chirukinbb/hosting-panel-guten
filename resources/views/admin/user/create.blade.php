@extends('layouts.admin')

@section('title') {{ __('admin/user.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <form action="{{ route('admin.user.store') }}" method="post"  enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('guest.name') }}</label>
            <input type="name" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('guest.password') }}</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('guest.confirm') }}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <input type="hidden" name="agree" value="1">
        <button type="submit" class="btn btn-primary">{{ __('header.register') }}</button>
{{--        <div class="row">--}}
{{--            <div class="col-8">--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="avatar" class="form-label">Avatar</label>--}}
{{--                    <input type="file" class="form-control" id="avatar" name="avatar">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="name" class="form-label">Name</label>--}}
{{--                    <input type="text" class="form-control" id="name" name="name">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="public_name" class="form-label">Public Name</label>--}}
{{--                    <input type="text" class="form-control" id="public_name" name="public_name">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="email" class="form-label">Email</label>--}}
{{--                    <input type="email" class="form-control" id="email" name="email">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="biography" class="form-label">Biography</label>--}}
{{--                    <textarea name="biography" id="biography" rows="10" class="form-control"></textarea>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-4 widgets-area">--}}
{{--                @include('admin.user.widgets.submit',compact('roles'))--}}
{{--            </div>--}}
        </div>
    </form>
@endsection
