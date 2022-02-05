<?php
/**
 * @var \App\Models\Article $article
 */
?>

@extends('layouts.app')

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
