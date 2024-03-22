@extends('Frontend::layouts.index')
@section('body-client')
    <!-- ================ start banner area ================= -->
    <!-- <section class="offer-image4">
        <div class="bg-overlay large-image-v1-gradient"></div>
        <div class="large-image-container">
            <img src="{{ asset('img/giayKham/giayKham1.png') }}" class="img-fluid"
                style="width: 100%; height: auto; object-fit: cover;">
        </div>
    </section> -->
    <section class="health-blog mt-3 mb-3">
        <div class="container-lg" style="background-color: #FAF8F3">
            <div class="text-center mt-3 mb-2">
                <h3 class="px-5 pt-3" style="line-height: normal">DỊCH VỤ LÀM GIẤY KHÁM SỨC KHỎE</h3>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 col-md-12 mt-3">
                        <section class="lattest-product-area pb-40 category-list">
                            <div class="row">
                                @foreach ($getBlog as $key => $getBlog)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card text-center card-product">
                                            <div class="card-product__img">
                                                <img style="height: 170px;width: 100%;object-fit: cover;" class="card-img" src="{{url('/file-image-client/blogs/')}}/{{$getBlog->blogImage->name_image ?? ''}}" alt="">
                                                <ul class="card-product__imgOverlay">
                                                    <a class="nav-link"
                                                        href="{{ url('/blog-detail/' . $getBlog->code_blog) }}"><button
                                                            class="btn btn-primary"
                                                            style="animation: lights 2s 750ms linear infinite;border-radius: 5px;">Đăng
                                                            ký</button></a>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-product__title1"><a
                                                    style="font-weight:600;color: #1c3361;"    href="{{ url('/blog-detail/' . $getBlog->code_blog) }}">{{ $getBlog->details->title }}</a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </section>
                        <!-- End Best Seller -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ top product area start ================= -->
    <section class="related-product-area">
        <div class="container mt-3">
            <div class="row mb-3">
                <div class="col-6">
                    <a href="#"><img src="img/giayKham/giayKham2.png" alt="" class="img-fluid"></a>
                </div>
                <div class="col-6">
                    <a href="#"><img src="img/giayKham/giayKham2.png" alt="" class="img-fluid"></a>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="image-card">
                        <img src="img/giayKham/giayKham3.png" alt="Giấy Khám Sức Khỏe" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-7 d-flex align-items-center justify-content-center mt-3 mt-md-0 mb-3 mt-md-0">
                    <div class="info-card">
                        <h4>LÀM GIẤY</h4>
                        <h1>Khám sức khỏe lấy ngay</h1>
                        <ul style="list-style-type: inherit; font-size:15px" class="px-3">
                            <li>Giấy khám sức khỏe xin việc, đi học, đi làm</li>
                            <li>Giấy khám sức khỏe lái xe</li>
                            <li>Giấy khám sức khỏe trẻ em</li>
                            <li>Giấy khám sức khỏe song ngữ</li>
                            <li>Giấy khám sức khỏe hoàn thiện hồ sơ</li>
                        </ul>
                        <!-- <a class="btn btn-primary mt-2" style="width:100%; ">ĐĂNG KÝ NHANH, GIẤY KHÁM ĐẠT CHẤT LƯỢNG</a> -->
                        <span class="btn btn-primary mt-2" style="width:100%; ">ĐĂNG KÝ NHANH, GIẤY KHÁM ĐẠT CHẤT LƯỢNG</span>

                    </div>
                </div>
            </div>
        </div>

    </section>
        <!--================Blog Area =================-->
        <section class="blog_area single-post-area py-80px section-margin--small">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 posts-list">
                    <div class="single-post row">
                        <div class="col-lg-12">
                            <h2>{!! $getBlogView->title !!}</h2>
                            {{-- <div class="feature-img">
                                <img class="img-fluid" src="img/blog/feature-img1.jpg" alt="">
                            </div> --}}
                        </div>
                        <div class="col-lg-12">
                            <div class="quotes">
                                {!! $getBlogView->decision !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================Blog Area =================-->
@endsection
