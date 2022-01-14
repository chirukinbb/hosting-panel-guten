@extends('layouts.app')

@section('title')
    {{ __('Log in') }} - {{ config('app.name') }}
@endsection

@section('content')
    <form  method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
    </form>
@endsection
