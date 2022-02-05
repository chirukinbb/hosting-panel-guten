<?php
    /**
     * @var Illuminate\Database\Eloquent\Collection $articles
     * @var \App\Models\Article $article
     */
?>

@extends('layouts.admin')

@section('title') {{ __('admin/article.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <a href="{{ route('admin.article.create') }}" class="btn btn-primary">{{ __('admin/article.create') }}</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('admin/article.name') }}</th>
            <th scope="col">{{ __('admin/article.date') }}</th>
            <th scope="col">{{ __('admin/article.status') }}</th>
            <th scope="col">{{ __('admin/article.actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @if($articles->count())
                @foreach($articles as $article)
                    <tr>
                        <th scope="row">{{ $article->id }}</th>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->created_at }}</td>
                        <td>{{ $article->getStatus() }}</td>
                        <td>
                            <a href="{{ route('admin.article.edit',['article'=>$article->id]) }}" class="btn btn-primary">{{ __('admin/article.edit') }}</a>
                            @if($article->deleted_at)
                                <a href="{{ route('admin.article.restore',['article'=>$article->id]) }}" class="btn btn-primary">{{ __('admin/article.restore') }}</a>
                            @else
                                <a href="{{ route('admin.article.hide',['article'=>$article->id]) }}" class="btn btn-danger">{{ __('admin/article.hidden') }}</a>
                            @endif
                            <form action="{{ route('admin.article.destroy',['article'=>$article->id]) }}"  class="d-inline-block" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">{{ __('admin/article.destroy') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="5">{{ __('admin/article.empty') }}</th>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
