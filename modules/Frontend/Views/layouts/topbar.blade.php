<div class="topbar">
    <div class="container">
        <!-- Add a phone number in the navbar -->
        <div class="navbar-text text-end">
            @if(empty($_SESSION["hoithicchc"]))
            <a class="text-decoration-none" href="{{ url('dang-nhap') }}">
                <span class="topbar-span cursor-pointer">
                    {{-- <img class="topbar-image" src="{{ asset('public/images/telephone.png') }}" alt="telephone"> --}}
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="topbar-span-text hide-smaller-768">Đăng nhập</span>
                </span>
            </a>
            <a class="text-decoration-none" href="{{ url('dang-ky') }}">
                <span class="topbar-span cursor-pointer" href="{{ url('dang-ky') }}">
                    {{-- <img class="topbar-image" src="{{ asset('public/images/email.png') }}" alt="email"> --}}
                    <i class="fas fa-user-plus"></i>
                    <span class="topbar-span-text hide-smaller-768">Đăng ký</span>
                </span>
            </a>
            @elseif(!empty($_SESSION["hoithicchc"]))
            <a class="dropdown-toggle text-decoration-none" href="javascript::void(0)" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                <span class="topbar-span-text hide-smaller-768">{{$_SESSION["hoithicchc"]['name']}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ url('tai-khoan') }}">Thông tin tài khoản</a></li>
                <li><a class="dropdown-item" href="{{ url('tai-khoan/doi-mat-khau') }}">Đổi mật khẩu</a></li>
                <li><a class="dropdown-item" href="{{ url('dang-xuat') }}">Thoát</a></li>
            </ul>
            @endif
        </div>
    </div>
</div>
