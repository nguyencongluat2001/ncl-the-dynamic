<nav class="navbar navbar-main navbar-expand-lg shadow-none border-radius-xl px-0 ms-4 me-2 mt-3 position-sticky blur shadow-blur top-1 z-index-sticky" id="navbarBlur"
    navbar-scroll="false">
    <div class="container-fluid py-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm text-dark opacity-5 breadcrumb2">@yield('breadcrumb1', '')</li>
                <li class="breadcrumb-item text-sm text-dark breadcrumb2">@yield('breadcrumb2', '')</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">@yield('pageName', '')</h6>
        </nav>

        <!-- User -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> Quản trị hệ thống
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ url('system/login/logout') }}">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <i class="fas fa-sign-out-alt" data-bs-toggle="tooltip" data-bs-title="Default tooltip"></i>
                                            <span class="font-weight-bold">Đăng xuất</span>
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
