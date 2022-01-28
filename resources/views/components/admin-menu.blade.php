<ul class="list-group">
    @foreach($adminMenuItems as $item)
        <li class="list-group-item">
            <a href="{{ route(($item === 'dashboard') ? 'admin' :'admin.'.$item) }}" class="nav-link">{{ __('admin.'.$item) }}</a>
        </li>
    @endforeach
</ul>
