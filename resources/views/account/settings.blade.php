@extends('layouts.account')

@section('title') {{ __('Account Settings') }} - {{ config('app.name') }} @endsection

@section('account')
    <form method="post" action="{{ route('account.page',['page'=>'settings']) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
            <input type="file" class="form-control" id="avatar" name="avatar">
        </div>
        <div class="mb-3 form-check">
            <label for="biography" class="form-label">{{ __('Biography') }}</label>
            <input type="checkbox" class="form-check-input" id="biography" name="biography">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
