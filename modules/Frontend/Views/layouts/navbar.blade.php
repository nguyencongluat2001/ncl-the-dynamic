<nav class="navbar navbar-expand-lg navbar-light" style="font-family: serif;">
    <div class="container">
        
       
       <div style="width: 200px">
          <a class="nav-link" href=""><img href="" class="card-img " src="../img/giay-kham-suc-khoe-ha-noi.png" alt="Card image"></a>
        </div>
        <!-- <span style="font-size: 20px;font-family: math;color: #55c2e6;">THE LIGHT</span> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
        <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
            <!-- <li class="nav-item active"><a class="nav-link" href="">TRANG CHỦ</a></li> -->
            <!-- <li class="nav-item submenu dropdown">
                <a href="shop" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">Cửa hàng</a>
                <ul class="dropdown-menu">
                <li class="nav-item"><a class="nav-link" href="shop">Tất cả sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="single-product.html">Product Details</a></li>
                <li class="nav-item"><a class="nav-link" href="checkout.html">Product Checkout</a></li>
                <li class="nav-item"><a class="nav-link" href="confirmation.html">Confirmation</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.html">Shopping Cart</a></li>
                </ul>
            </li> -->
            <li class="nav-item"><a class="nav-link" href="blog">LÀM GIẤY KHÁM SỨC KHỎE</a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="contact">Liên hệ</a></li> -->
            <li class="nav-item"><a class="nav-link" href="contact">BẰNG LÁI XE - BẰNG C2,C3,ĐẠI HỌC,..</a></li>
        </ul>

        <ul class="nav-shop">
        <li class="nav-item"><button><i class="ti-search"></i></button></li>
        <li class="nav-item"><button><i class="ti-shopping-cart"></i><span class="nav-shop__circle">3</span></button> </li>
        <div class="navbar-text text-end">
            @if(empty($_SESSION['name']))
            <a class="text-decoration-none" href="{{ url('login') }}">
                <span class="topbar-span cursor-pointer">
                    <li class="nav-item"><a class="button button-header" href="login">Đăng nhập</a></li>
                </span>
            </a>
            @elseif(!empty($_SESSION['name']))
            <li class="nav-item submenu dropdown">
                <a href="shop" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false"><i class="fas fa-user-circle"></i> {{$_SESSION['name']}}</a>
                <ul class="dropdown-menu">
                    <li class="nav-item"><a class="nav-link" href="info">Thông tin tài khoản</a></li>
                    <li class="nav-item"><a class="nav-link" href="single-product.html">Đổi mật khẩu</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout">Thoát</a></li>
                </ul>
            </li>
            @endif
        </div>
        </ul>
    </div>
    </div>
</nav>
