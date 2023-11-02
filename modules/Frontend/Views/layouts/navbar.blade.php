<!-- Bootstrap Navbar -->
<nav id="navbar_top" class="navbar navbar-main navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @if(isset($menuItems))
                @foreach($menuItems as $menu)
                @php $active =  request()->is('/') ? true : false;  @endphp
                <li class="nav-item {{ request()->is($menu->slug) || ($active && $menu->id_menu == 'trang-chu') ? 'active' : '' }}" id="navbar-{{ $menu->id_menu }}">
                    <a class="nav-link" href="{{ url('/' . $menu->slug) }}">{{ $menu->name ?? '' }}</a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</nav>