@extends('layouts.admin')

@section('title')
    {{ config('app.name') }} - {{ __('admin/settings.title') }}
@endsection

@section('content')
    <form action="{{ route('admin.settings.save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="logo" class="form-label h4">{{ __('admin/settings.logo') }}</label>
            @if(\App\Facades\Setting::get('logo'))
                <div class="mb-2 text-center">
                    <img src="{{ \App\Facades\Setting::url('logo') }}" class="img-fluid" alt="">
                </div>
            @endif
            <input type="file" class="form-control" id="logo" name="setting[logo]">
        </div>
        <button type="submit" class="btn btn-primary">{{ __('admin/settings.submit') }}</button>
    </form>
@endsection

