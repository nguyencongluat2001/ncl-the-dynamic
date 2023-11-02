<div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 text-white">

    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="account_sidebar">

        <li>
            <a id="account_sidebar_info" href="{{ url('tai-khoan') }}" class="nav-link px-0 align-middle">
                <i class="fas fa-user"></i>
                <span class="ms-1 d-none d-sm-inline">Thông tin tài khoản</span>
            </a>
        </li>

        <li>
            <a id="account_sidebar_history" href="{{ url('tai-khoan/lich-su-bai-thi') }}" class="nav-link px-0 align-middle">
                <i class="fas fa-clipboard-list"></i>
                <span class="ms-1 d-none d-sm-inline">Lịch sử bài thi</span>
            </a>
        </li>

        <!-- menu cấp 2 nếu cần -->
        <!-- <li>
            <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 d-none d-sm-inline">Bootstrap</span></a>
            <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                <li class="w-100">
                    <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 1</a>
                </li>
                <li>
                    <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 2</a>
                </li>
            </ul>
        </li> -->

    </ul>
</div>