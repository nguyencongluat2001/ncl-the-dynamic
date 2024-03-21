@extends('Frontend::layouts.index')
@section('body-client')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\client\JS_Blog.js') }}"></script>
    <!-- ================ start banner area ================= -->
    <section class="blog-banner-area" id="blog">
        <div class="container h-100">
            <div class="blog-banner">
                <div class="text-center">
                    <h1>Blog Details</h1>
                    <nav aria-label="breadcrumb" class="banner-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ end banner area ================= -->



    <!--================Blog Area =================-->
    <section class="blog_area single-post-area py-80px section-margin--small">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 posts-list">
                    <div class="single-post row">
                        <div class="col-lg-12">
                            <h2>{!! $blogs_health->title !!}</h2>
                            {{-- <div class="feature-img">
                                <img class="img-fluid" src="img/blog/feature-img1.jpg" alt="">
                            </div> --}}
                        </div>
                        <div class="col-lg-12">
                            <div class="quotes">
                                {!! $blogs_health->decision !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================Blog Area =================-->
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
                        @if ($blogs_health->code_blog == '2024_03_15_1520000000665299')
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
                                <button onclick="JS_Blog.store('form#frmHealth_index')" id='btn_create'
                                    class="button btn-primary mt-3" type="button">Gửi cộng tác
                                    viên</button>
                            </div>
                        @else
                            <input type="hidden" name="code_category" id="code_category"
                                value="{{ $getBlog->code_category }}">
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
                                    <input type="date" class="form-control " id="dateOfBirth" name="dateOfBirth"
                                        required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control " id="email" name="email"
                                        placeholder="Nhập địa chỉ email" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="school" class="form-label">Trường học</label>
                                    <input type="text" class="form-control " id="school" name="school"
                                        placeholder="Nhập trường học của bạn" required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="industry" class="form-label">Ngành</label>
                                    <input type="text" class="form-control " id="industry" name="industry"
                                        placeholder="Nhập ngành của bạn" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="graduate_time" class="form-label">Tốt nghiệp năm</label>
                                    <input type="date" class="form-control " id="graduate_time" name="graduate_time"
                                        placeholder="Tốt nghiệp năm" required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="level" class="form-label">Xếp loại</label>
                                    <input type="text" class="form-control " id="level" name="level"
                                        placeholder="Xếp loại" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="permanent_residence" class="form-label">Hộ khẩu cư trú</label>
                                    <input type="text" class="form-control " id="permanent_residence"
                                        name="permanent_residence" placeholder="Hộ khẩu của bạn" required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="identity" class="form-label">Căn cước</label>
                                    <input type="text" class="form-control " id="identity" name="identity"
                                        placeholder="Căn cước của bạn" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="identity_time" class="form-label">Ngày cấp căn cước</label>
                                    <input type="date" class="form-control " id="identity_time" name="identity_time"
                                        placeholder="Ngày cấp căn cước" required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Địa chỉ của bạn</label>
                                    <input type="text" class="form-control " id="address" name="address"
                                        placeholder="Nhập nơi cấp căn cước" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="identity_address" class="form-label">Nơi cấp căn cước</label>
                                    <input type="text" class="form-control " id="identity_address"
                                        name="identity_address" placeholder="Nhập nơi cấp căn cước" required autofocus>
                                </div>
                            </div>
                            <div class="row px-5 mb-2">
                                <div class="col-md-6">
                                    <label for="avatar1" class="label-upload">Chọn ảnh của bạn</label>
                                    <input type="file" name="avatar1" id="avatar1"
                                        onchange="readURL(this, 'show_avatar_img')"><br>
                                    @if (!empty($data['avatar1']))
                                        <img id="show_avatar_img"
                                            src="{{ url('/file-image-client/bangcap/') }}/{{ $data['avatar1'] }}"
                                            alt="Image" style="width:150px">
                                    @else
                                        <img id="show_avatar_img" hidden alt="Image" style="width:150px">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="image_transfer" class="label-upload">Chọn ảnh chuyển khoản</label>
                                    <input type="file" name="image_transfer" id="image_transfer"
                                        onchange="readURL(this, 'show_transfer_img')"><br>
                                    @if (!empty($data['image_transfer']))
                                        <img id="show_transfer_img"
                                            src="{{ url('/file-image-client/bangcap/') }}/{{ $data['image_transfer'] }}"
                                            alt="Image" style="width:150px">
                                    @else
                                        <img id="show_transfer_img" hidden alt="Image" style="width:150px">
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button onclick="JS_Blog.storeBangCap('form#frmHealth_index')" id='btn_create'
                                    class="button btn-primary mt-3" type="button">Gửi cộng tác
                                    viên</button>
                            </div>
                        @endif




                    </form>
                </div>

            </div>
        </div>
    </section>

    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = "{{ url('') }}";
        var JS_Blog = new JS_Blog(baseUrl, 'blog-detail', {!! $blogs_health->code_blog !!});
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

        function readURL(input, imgId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + imgId).attr('src', e.target.result).width(150).removeAttr('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
