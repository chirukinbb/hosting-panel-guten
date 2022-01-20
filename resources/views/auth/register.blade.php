@extends('layouts.app')

@section('title')
    {{ __('header.register') }} - {{ config('app.name') }}
@endsection

@section('content')
    <form  method="POST" action="{{ route('register') }}">
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
        <button type="submit" class="btn btn-primary">{{ __('header.register') }}</button>
    </form>
@endsection
