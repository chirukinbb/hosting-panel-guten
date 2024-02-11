@extends('layouts.app')

@section('title')
    {{ __('header.game') }} - {{ config('app.name') }}
@endsection

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ $token }}">
@endsection

@section('content')
    <div id="game"></div>
@endsection

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
