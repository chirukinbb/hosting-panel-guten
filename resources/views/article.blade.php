<?php
/**
 * @var \App\Models\Article $article
 */
?>

@extends('layouts.app')

@section('meta')
    <meta name="description" content="{{ $article->meta_content }}">
    <meta name="robots" content="all">
    <meta name="keywords" content="{{ $article->keywords }}">
@endsection

@section('title')
    {{ $article->title }} - {{ config('app.name') }}
@endsection

@section('content')
    <main>
        <h1>{{ $article->title }}</h1>
        <time>{{ $article->created_at }}</time>
        {!! $article->content !!}
    </main>
@endsection
