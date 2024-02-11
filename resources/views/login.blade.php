@extends('layouts.app')

@section('content')
    <div class="flex flex-column justify-content-center" style="height: 100vh;max-width: 250px;margin: auto;">
        <form action="{{route('login')}}" method="post" style="">
            <input type="email" class="form-control mb-3" placeholder="Email" name="email">
            <input type="password" class="form-control mb-3" placeholder="Password" name="password">
            <button type="submit" class="btn btn-outline-secondary w-100 text-center mb-3">Login</button>
            @csrf
        </form>
        <div class="socials">
            <p class="text-center mb-3">or</p>
            <a href="{{route('google.oauth')}}" class="btn btn-outline-secondary w-100 text-center">login with
                Google</a>
        </div>
    </div>
@endsection
