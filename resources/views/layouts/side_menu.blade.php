<aside>
    <div class="sidebar-header">
        <h3 class="brand">
            <span>SimpleDash</span>
        </h3>
        <div class="menu-close">
            <i class="far fa-times-circle"></i>
        </div>
    </div>

    <div class="sidebar-menu">
        <ul>
            @foreach($dataGetter->getDefaultSidemenuItems() as $item)
                @if(($item->is_admin_menu && auth()->user()->is_admin) || $item->is_default_menu)
                    <li class="menu-item">
                        <a href="{{route($item->route)}}" class="{{request()->is(explode('.',$item->route)[0] . '*') ? 'active' : ''}}">
                            <span class="{{$item->icon}}"></span>
                            <span class="menu-text">{{$item->name}}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>
