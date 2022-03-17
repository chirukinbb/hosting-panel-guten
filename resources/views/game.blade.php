@extends('layouts.app')

@section('title')
    {{ __('header.game') }} - {{ config('app.name') }}
@endsection

@section('content')
    <div id="app">
        <example-component></example-component>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
