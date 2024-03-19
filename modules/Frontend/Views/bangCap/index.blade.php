@extends('Frontend::layouts.index')
@section('body-client')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\client\JS_BangCap.js') }}"></script>

    <section class="health-blog mt-3 mb-3">
        <div class="container-lg" style="background-color: #FAF8F3">
            <div class="text-center mt-3 mb-2">
                <h6 class="px-5 pt-3" style="line-height: normal">Ở ĐÂY</h6>
                <h3 class="px-5 pt-3" style="line-height: normal">CHÚNG TÔI CÓ BẰNG CẤP</h3>
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
                                                <h4 class="card-product__title1"><a
                                                        href="#">{{ $blogDetail->title }}</a></h4>
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
    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = "{{ url('') }}";
        var JS_BangCap = new JS_BangCap(baseUrl, 'giaykham');
        // jQuery(document).ready(function($) {
        //     JS_BangCap.loadIndex(baseUrl);
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
