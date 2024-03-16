@extends('Frontend::layouts.index')
@section('body-client')
    <!-- ================ start banner area ================= -->
    <section class="blog-banner-area" id="category">
        <div class="container-xl h-100"
            style="background: url('{{ asset('img/giayKham/giayKham1.png') }}') no-repeat center center; background-size: cover;">
        </div>
    </section>

    <!-- ================ top product area start ================= -->
    <section class="related-product-area">
        <div class="container mt-3">
            <div class="row mb-3">
                <div class="col-sm-4 col-xl-4">
                    <a href="#"><img src="img/giayKham/giayKham2.png" alt="" class="img-fluid"></a>
                </div>
                <div class="col-sm-4 col-xl-4">
                    <a href="#"><img src="img/giayKham/giayKham2.png" alt="" class="img-fluid"></a>
                </div>
                <div class="col-sm-4 col-xl-4">
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
                        <a href="#" class="btn btn-primary mt-2" style="width:100%; ">XEM MẪU CÁC LOẠI Ở ĐÂY</a>
                    </div>
                </div>
            </div>
        </div>

    </section>

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
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product1.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Accessories</p>
                                            <h4 class="card-product__title"><a href="#">Quartz Belt Watch</a></h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product2.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Beauty</p>
                                            <h4 class="card-product__title"><a href="#">Women Freshwash</a></h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product3.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Decor</p>
                                            <h4 class="card-product__title"><a href="#">Room Flash Light</a>
                                            </h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product4.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Decor</p>
                                            <h4 class="card-product__title"><a href="#">Room Flash Light</a>
                                            </h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product5.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Accessories</p>
                                            <h4 class="card-product__title"><a href="#">Man Office Bag</a></h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product6.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Kids Toy</p>
                                            <h4 class="card-product__title"><a href="#">Charging Car</a></h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product7.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Accessories</p>
                                            <h4 class="card-product__title"><a href="#">Blutooth Speaker</a>
                                            </h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product8.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Kids Toy</p>
                                            <h4 class="card-product__title"><a href="#">Charging Car</a></h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card text-center card-product">
                                        <div class="card-product__img">
                                            <img class="card-img" src="img/product/product1.png" alt="">
                                            <ul class="card-product__imgOverlay">
                                                <li><button><i class="ti-search"></i></button></li>
                                                <li><button><i class="ti-shopping-cart"></i></button></li>
                                                <li><button><i class="ti-heart"></i></button></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <p>Accessories</p>
                                            <h4 class="card-product__title"><a href="#">Quartz Belt Watch</a>
                                            </h4>
                                            <p class="card-product__price">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- End Best Seller -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
