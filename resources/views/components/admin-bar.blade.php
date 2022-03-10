<div class="container-fluid">
    <style>
        body{
            padding-top: 25px;
            position: relative;
        }
    </style>
    <div class="admin-bar d-flex justify-content-between container-fluid top-0 position-fixed bg-white" style="z-index: 999;">
        @if(! \Illuminate\Support\Str::contains(request()->route()->getName(),'admin'))
            <a href="{{ route('admin') }}">{{ __('header.admin') }}</a>
        @else
            <a href="{{ route('home') }}">{{ __('header.home') }}</a>
        @endif
        <a href="{{ route('admin.user.edit',['user'=>Auth::id()]) }}">Hello, mr(ms) {{ Auth::user()->data->public_name }}</a>
    </div>
</div>
