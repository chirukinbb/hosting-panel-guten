<?php
    /**
     * @var \Illuminate\Pagination\LengthAwarePaginator $users
     * @var \App\Models\User  $user
     */
?>

@extends('layouts.admin')

@section('title') {{ __('admin/user.title') }} - {{ config('app.name') }} @endsection

@section('content')
    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">{{ __('admin/user.create') }}</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('admin/user.name') }}</th>
            <th scope="col">{{ __('admin/user.date') }}</th>
            <th scope="col">{{ __('admin/user.roles') }}</th>
            <th scope="col">{{ __('admin/user.actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @if($users->isNotEmpty())
                @foreach($users->items() as $user)
                    @if($user->id !== Auth::id())
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ implode(',',$user->getRoleNames()->toArray()) }}</td>
                            <td>
                                <a href="{{ route('admin.user.edit',['user'=>$user->id]) }}" class="btn btn-primary">{{ __('admin/user.edit') }}</a>
                                @if($user->deleted_at)
                                    <a href="{{ route('admin.user.approved',['user'=>$user->id]) }}" class="btn btn-primary">{{ __('admin/user.approved') }}</a>
                                @else
                                    <a href="{{ route('admin.user.banned',['user'=>$user->id]) }}" class="btn btn-danger">{{ __('admin/user.banned') }}</a>
                                @endif
                                <form action="{{ route('admin.user.destroy',['user'=>$user->id]) }}"  class="d-inline-block" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">{{ __('admin/user.destroy') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <th colspan="5">{{ __('admin/user.empty') }}</th>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
