@extends('Frontend::layouts.index')
@section('body-client')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\client\JS_Health.js') }}"></script>
    <!-- ================ start banner area ================= -->
    <section class="offer-image4">
        <div class="bg-overlay large-image-v1-gradient"></div>
        <div class="large-image-container">
            <img src="{{ asset('img/giayKham/giayKham1.png') }}" class="img-fluid"
                style="width: 100%; height: auto; object-fit: cover;">
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
                                @foreach ($blogs_health as $key => $blogDetail)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card text-center card-product">
                                            <div class="card-product__img">
                                                <img class="card-img" src="img/giayKham/giayKham4.jpg" alt="">
                                                <ul class="card-product__imgOverlay">
                                                    <a class="nav-link" href="bang"><button class="btn btn-primary"
                                                            style="animation: lights 2s 750ms linear infinite;border-radius: 5px;">Đăng
                                                            ký</button></a>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                {{-- <p>Accessories</p> --}}
                                                <h4 class="card-product__title1"><a
                                                        href="#">{{ $blogDetail->title }}</a></h4>
                                                {{-- <p class="card-product__price">$150.00</p> --}}
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
    <!-- ================ Subscribe section start ================= -->
    <section class="subscribe-position">
        <div class="container">
            <div class="subscribe">
                <h5 class="subscribe__title text-center" style="color:#dd3333">THÔNG TIN LIÊN HỆ</h5>
                <p class="text-center">Quý khách vui lòng để lại thông tin liên hệ</p>
                <div id="mc_embed_signup">
                    <form action="" role="form" method="POST" id="frmHealth_index" class="mt-3 pt-1"
                        enctype="multipart/form-data">
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <div class="row px-5 mb-2">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nhập họ và tên" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control " id="phone" name="phone"
                                    placeholder="Nhập số điện thoại" required autofocus>
                            </div>
                        </div>
                        <div class="row px-5 mb-2">
                            <div class="col-md-6">
                                <label for="validationServer01" class="form-label">Giới tính</label>
                                <div class="d-flex">
                                    <div class="form-check" style="margin-right: 20px;">
                                        <input class="form-check-input" type="radio" name="sex" id="male"
                                            value="male" checked>
                                        <label class="form-check-label" for="male">
                                            Nam
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" id="female"
                                            value="female">
                                        <label class="form-check-label" for="female">
                                            Nữ
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="dateOfBirth" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control " id="dateOfBirth" name="dateOfBirth" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="row px-5 mb-2">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control " id="email" name="email"
                                    placeholder="Nhập địa chỉ email" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="history" class="form-label">Lịch sử bệnh lý</label>
                                <input type="text" class="form-control " id="history" name="history"
                                    placeholder="Nhập lịch sử bệnh lý" required autofocus>
                            </div>
                        </div>
                        <div class="row px-5 mb-2">
                            <div class="col-md-6">
                                <label for="weighed" class="form-label">Cân nặng</label>
                                <input type="email" class="form-control " id="weighed" name="weighed"
                                    placeholder="Nhập cân nặng của bạn" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="height" class="form-label">Chiều cao</label>
                                <input type="text" class="form-control " id="height" name="height"
                                    placeholder="Nhập chiều cao của bạn" required autofocus>
                            </div>
                        </div>
                        <div class="row px-5 mb-2">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control " id="address"
                                    placeholder="Nhập địa chỉ chi tiết" required autofocus>
                            </div>
                        </div>
                        <div class="row px-5 mb-2">
                            <div class="col-md-12">
                                <label for="avatar" class="label-upload">Chọn ảnh</label>
                                <input type="file" name="avatar" id="avatar" onchange="readURL(this)"><br>
                                @if (!empty($data['avatar']))
                                    <img id="show_img"
                                        src="{{ url('/file-image-client/giaykham/') }}/{{ $data['avatar'] }}"
                                        alt="Image" style="width:150px">
                                @else
                                    <img id="show_img" hidden alt="Image" style="width:150px">
                                @endif
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button onclick="JS_Health.store('form#frmHealth_index')" id='btn_create'
                                class="button btn-primary mt-3" type="button">Gửi cộng tác
                                viên</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>

    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = "{{ url('') }}";
        var JS_Health = new JS_Health(baseUrl, 'giaykham');
        // jQuery(document).ready(function($) {
        //     JS_Health.loadIndex(baseUrl);
        // })
    </script>

    <script src="../assets/js/croppie.js"></script>
    <script src="../assets/js/croppie.min.js"></script>
    <script>
        $(document).ready(function() {
            $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },

                boundary: {
                    width: 300,
                    height: 300
                }
            });

            $('#upload_image').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    })
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimage').show();
            });

            $('.crop_image').click(function(event) {
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response) {
                    console.log(1, $image_crop)
                });
            })
        })

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#show_img').attr('src', e.target.result).width(150);
                };
                $("#show_img").removeAttr('hidden');

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
