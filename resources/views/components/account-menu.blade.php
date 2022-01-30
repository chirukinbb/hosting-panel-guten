<ul class="list-group border-0 rounded-0">
    @foreach($accountMenuItems as $item)
        <li class="list-group-item border-end-0 border-start-0 bg-light border-white">
            <a href="{{ route('account.page',['page'=>$item]) }}" class="nav-link text-black-50">{{ __('account.'.$item) }}</a>
        </li>
    @endforeach
</ul>
