@extends('layouts.account')

@section('title') {{ __('account/settings.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <form method="post" action="{{ route('account.page',['page'=>'settings']) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 {{ is_null($settings) ? 'd-none' : '' }}">
            <label for="avatar" class="form-label h4">{{ __('account/dashboard.avatar') }}</label>
            <div class="mb-2 text-center">
                <img src="{{ asset($settings->avatar_url) }}" width="100" height="100" class="rounded-circle" alt="">
            </div>
            <input type="file" class="form-control" id="avatar" name="avatar">
        </div>
        <div class="mb-3">
            <label for="biography" class="form-label h4">{{ __('account/dashboard.biography') }}</label>
            <textarea cols="8" class="form-control" id="biography" name="biography">{{ $settings->biography }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('account/dashboard.submit') }}</button>
    </form>
@endsection
