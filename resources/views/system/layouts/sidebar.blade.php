@php
use Modules\Core\Helpers\MenuSystemHelper;
@endphp

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main" data-color="info" style="left: -10px">
    <!-- Header Sidebar -->
    <div class="sidenav-header" style="text-align: center; padding-top:15px;">
        <h6 class="font-weight-bolder mb-0">QUẢN TRỊ HỆ THỐNG</h6>
        <h6 class="font-weight-bolder mb-0">THI TRỰC TUYẾN</h6>
    </div>

    <hr class="horizontal dark mt-0">

    <!-- Menu -->
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="height: auto;">

        <ul class="navbar-nav menu">

            @foreach ($menuItems as $url => $menu)
            @php
            echo MenuSystemHelper::print_menu($url, $menu, $module, $childModule);
            @endphp
            @endforeach
        </ul>
    </div>
</aside>