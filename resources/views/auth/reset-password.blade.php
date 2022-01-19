@extends('layouts.app')

@section('title')
    {{ __('Reset password') }} - {{ config('app.name') }}
@endsection

@section('content')
    <form  method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <button type="submit" class="btn btn-primary">{{ __('Reset') }}</button>
    </form>
@endsection
