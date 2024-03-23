@extends('Frontend::layouts.index')
@section('body-client')
    <style>
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: 2px solid #df4e4e;
        }
    </style>
    <section class="blog_categorie_area_nc">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h2><strong>Dịch vụ làm giấy khám sức khỏe, <span class="d-lg-block d-inline">bằng lái
                                xe</span></strong>
                    </h2>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="text-md-end text-start">
                        <p>Dịch vụ làm giấy khám sức khỏe xin việc, lái xe, bổ sung hồ sơ…
                            GKSK A3 có giáp lai ảnh, ship cod tận nơi, lấy ngay trong ngày theo thông tư TT14/2013/BYT và
                            TT32/2023/BYT</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container-lg">
            <img src="img/home/banner.png" alt="" class="img-fluid" style="width: 100%;">
        </div>
    </section>

    <section class="blog_categorie_area_nc mt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2><strong>DỊCH VỤ LÀM GIẤY KHÁM SỨC KHỎE TOÀN QUỐC</strong></h2>
                    <p>Chuyên làm giấy khám sức khỏe các loại, giấy khám sức khỏe a3, a4 theo thông tư 14, làm giấy khám sức
                        khỏe xin việc, giấy khám sức khỏe lái xe...</p>
                    <div class="card mb-3" style="border-radius: 10px;background-color: #f4f4f4;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fas fa-thumbs-up fa-lg fa-2x primary font-large-2 float-left"
                                            style="color: #df4e4e;"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h3 style="color: #df4e4e;">GIÁ TIỀN</h3>
                                        <span>Giá luôn rẻ nhất</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3" style="border-radius: 10px;background-color: #f4f4f4;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fas fa-star fa-lg fa-2x primary font-large-2 float-left"
                                            style="color: #df4e4e;"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h3 style="color: #df4e4e;">Dịch vụ</h3>
                                        <span>Dịch vụ đa dạng nhất</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3" style="border-radius: 10px;background-color: #f4f4f4;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i class="fas fa-star fa-lg fa-2x primary font-large-2 float-left"
                                            style="color: #df4e4e;"></i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h3 style="color: #df4e4e;">Chất lượng</h3>
                                        <span>Chất lượng tốt nhất</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3" style="border-radius: 10px;background-color: #f4f4f4;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-center">
                                        <i
                                            style="color: #df4e4e; font-size: 2em; display: inline-flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                            </svg>
                                        </i>
                                    </div>
                                    <div class="media-body text-right">
                                        <h3 style="color: #df4e4e;">Thời gian</h3>
                                        <span>Thời gian linh hoạt nhất</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 d-sm-block d-none">
                    <img src="img/home/banner1.png" alt="" class="img-fluid mb-3">
                    <img src="img/home/banner2.png" alt="" class="img-fluid d-lg-none d-sm-block d-none">
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- ================ top product area end ================= -->
    <!-- ================ Best Selling item  carousel ================= -->
    <section class="blog_categorie_area_nc mt-3">
        <div class="container">
            <div class="row">
                <div class="col-50nc col-md-6 col-lg-4 col-xl-6">
                    <div class="card text-center card-product">
                        <div class="card-product__img">
                            <img style="height: 50%;object-fit: cover;" class="card-img"
                                src="../img/home/lam-giay-kham-suc-khoe-nhanh-chong.jpg" alt="">
                            <ul class="card-product__imgOverlay">
                                <!-- <li><button><i class="ti-search"></i></button></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <li><button><i class="ti-shopping-cart"></i></button></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <li><button><i class="ti-heart"></i></button></li> -->
                                <a class="nav-link" href="giaykham"><button class="btn btn-success"
                                        style="animation: lights 2s 750ms linear infinite;border-radius: 5px;">Đăng
                                        ký</button></a>

                            </ul>
                            <br>
                        </div>
                        <div class="card-body">
                            <!-- <p>Làm chuẩn, ship nhanh</p> -->
                            <h4 style="font-family: system-ui;" class="card-product__title"><a href="single-product.html"
                                    style="color: #9d0000;">LÀM GIẤY KHÁM SỨC KHỎE LẤY NGAY</a></h4>
                            <p class="card-product__price">150,000 - 300,000 VND </p>
                        </div>
                    </div>
                </div>
                <div class="col-50nc col-md-6 col-lg-4 col-xl-6">
                    <div class="card text-center card-product">
                        <div class="card-product__img">
                            <img style="height: 50%;object-fit: cover;" class="card-img" src="../img/home/bang-c2-dh.jpg"
                                alt="">
                            <ul class="card-product__imgOverlay">
                                <!-- <li><button><i class="ti-search"></i></button></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <li><button><i class="ti-shopping-cart"></i></button></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <li><button><i class="ti-heart"></i></button></li> -->
                                <a class="nav-link" href="bang"><button class="btn btn-success"
                                        style="animation: lights 2s 750ms linear infinite;border-radius: 5px;">Đăng
                                        ký</button></a>
                            </ul>
                        </div>
                        <div class="card-body">
                            <!-- <p>Chính xác, rõ ràng, hỏa tốc</p> -->
                            <h4 style="font-family: system-ui;" class="card-product__title"><a href="single-product.html"
                                    style="color: #9d0000;">LÀM BẰNG LÁI XE, C2 - ĐẠI HỌC, TIẾN SĨ </a></h4>
                            <p class="card-product__price">150,000 - 1,500,000 VNĐ </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blog">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-12 mb-12 mb-lg-0">
                    <div class="card">
                        <div class="card-body" style="background: #f8f7f4">
                            <div class="banner-content col-lg-12 col-12 m-lg-auto text-left content-reader"
                                id="content-reader">
                                <div style="color:#264451; width: 100%;" class="light-300 text-justify">
                                    {!! $blogs_details_bang->decision !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <!-- ================ Blog section end ================= -->

    <!-- ================ Blog section start ================= -->
    <section class="blog">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-12 mb-12 mb-lg-0">
                    <div class="card">
                        <div class="card-body" style="background: #f8f7f4">
                            <div class="banner-content col-lg-12 col-12 m-lg-auto text-left content-reader"
                                id="content-reader">
                                <div style="color:#264451; width: 100%;" class="light-300 text-justify">
                                    {!! $blogs_details->decision !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ Blog section end ================= -->
    <br>

    <!-- ================ Subscribe section start ================= -->
    <section class="subscribe-position">
        <div class="container">
            <div class="subscribe text-center">
                <!-- <h5 class="subscribe__title">Nhận cập nhật từ mọi nơi</h5> -->
                <p>Quý khách vui lòng để lại thông tin liên hệ</p>
                <div id="mc_embed_signup">
                    <form target="_blank"
                        action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                        method="get" class="subscribe-form form-inline mt-5 pt-1">
                        <div class="form-group ml-sm-auto">
                            <input class="form-control mb-1" type="email" name="email"
                                placeholder="Email hoặc số điện thoại" onfocus="this.placeholder = ''"
                                onblur="this.placeholder = 'Your Email Address '">
                            <div class="info"></div>
                        </div>
                        <button class="button button-subscribe mr-auto mb-1" type="submit">Gửi cộng tác viên</button>
                        <div style="position: absolute; left: -5000px;">
                            <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value=""
                                type="text">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- ================ Subscribe section end ================= -->
@endsection
