@extends('layouts.app')

@section('title')
    {{ __('Forgot your password?') }} - {{ config('app.name') }}
@endsection

@section('content')
    <form  method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Email Password Reset Link') }}</button>
    </form>
@endsection
