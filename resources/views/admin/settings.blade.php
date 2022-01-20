@extends('layouts.layouts.admin')

@section('title')
    {{ config('app.name') }} - {{ __('App Settings') }}
@endsection

@section('content')
    <form action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="logo">
            @if(\App\Facades\Setting::get('logo'))
                <img src="{{ \App\Facades\Setting::get('logo') }}" class="img-fluid" alt="">
            @endif
            <input type="file" name="settings[logo]">
        </div>
    </form>
@endsection

