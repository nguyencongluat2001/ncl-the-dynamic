<div class="banner">
    <div class="container banner-container">
        <div>
            <a href="{{ url('') }}">
                <img class="banner-image" src="{{ asset('public/images/quoc-huy.png') }}" alt="banner">
                <div class="">
                    <h3 class="first-text">Tỉnh Hải Dương</h3>
                    <span class="second-text">Chung tay cải cách hành chính</span>
                </div>
            </a>
        </div>
        <div class="banner-button">
            @if (!empty($_SESSION['hoithicchc']))
                <button type="button" class="btn-custom btn-custom-white dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{ asset('public/images/user.png') }}" alt=""
                        style="height: 23px; margin-right: 5px;vertical-align: top;">
                    {{ $_SESSION['hoithicchc']['name'] }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ url('tai-khoan') }}">Thông tin tài khoản</a></li>
                    <li><a class="dropdown-item" href="{{ url('tai-khoan/doi-mat-khau') }}">Đổi mật khẩu</a></li>
                    <li><a class="dropdown-item" href="{{ url('dang-xuat') }}">Thoát</a></li>
                </ul>
            @else
                <a class="btn-custom btn-custom-white me-2" href="{{ url('dang-ky') }}" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" data-bs-title="Đăng ký">Đăng ký</a>
                <a class="btn-custom btn-custom-blue" href="{{ url('dang-nhap') }}" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" data-bs-title="Đăng nhập">Đăng nhập</a>
            @endif
        </div>
    </div>
</div>
