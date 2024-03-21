@extends('Frontend::layouts.index')
@section('body-client')
    <section class="health-blog mt-3 mb-3">
        <div class="container-lg" style="background-color: #FAF8F3">
            <div class="text-center mt-3 mb-2">
                <h6 class="px-5 pt-3" style="line-height: normal">Ở ĐÂY</h6>
                <h3 class="px-5" style="line-height: normal">CHÚNG TÔI CÓ BẰNG CẤP</h3>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 col-md-12 mt-3">
                        <section class="lattest-product-area pb-40 category-list">
                            <div class="row">
                                @foreach ($blogs_health as $key => $blogDetail)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card text-center card-product">
                                            <div class="card-product__img">
                                                <img class="card-img" src="img/giayKham/giayKham4.jpg" alt="">
                                                <ul class="card-product__imgOverlay">
                                                    <a class="nav-link"
                                                        href="{{ url('/blog-detail/' . $blogDetail->code_blog) }}"><button
                                                            class="btn btn-primary"
                                                            style="animation: lights 2s 750ms linear infinite;border-radius: 5px;">Đăng
                                                            ký</button></a>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-product__title1"><a
                                                        href="{{ url('/blog-detail/' . $blogDetail->code_blog) }}">{{ $blogDetail->title }}</a>
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
@endsection