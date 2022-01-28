<ul class="list-group">
    @foreach($accountMenuItems as $item)
        <li class="list-group-item">
            <a href="{{ route('account.page',['page'=>$item]) }}" class="nav-link">{{ __('account.'.$item) }}</a>
        </li>
    @endforeach
</ul>
