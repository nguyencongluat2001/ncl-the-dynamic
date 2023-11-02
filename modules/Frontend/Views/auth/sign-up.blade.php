@extends('Frontend::layouts.index')

@section('style')
    <style>
        .invalid-email {
            font-style: italic;
            font-size: 15px;
            color: red;
            display: none;
        }
    </style>
@endsection

@section('script')
    <script>
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
            var baseUrl = '{{ url('') }}';

            function validateCCCD(e) {
                $('#cmnd').val(e.value.replace(/[^\d]/g, ''));
            }

            function validateEmail(e) {
                // let input = e.value;
                // let pattern = /^[\w-\.]+(@haiduong.gov.vn){1}$/i;
                // if (!pattern.test(input)) {
                //     $('.invalid-email').show();
                // } else {
                //     $('.invalid-email').hide();
                // }
            }

            function changeUnitlevel(e) {
                $('#don_vi').html('');
                if (typeof e.value == 'string' && e.value !== '') {
                    $.ajax({
                        type: "GET",
                        url: baseUrl + '/data/unit-by-level/' + e.value,
                        dataType: "html",
                        success: function(res) {
                            $('#don_vi').append(res);
                            $('.chzn-select').trigger('chosen:updated');
                        }
                    });
                }
            }

            function signUp() {
                let data = $('#frm_sign_up').serialize();
                $.ajax({
                    type: "POST",
                    url: baseUrl + '/dang-ky',
                    data: data,
                    dataType: "json",
                    success: function(arrResult) {
                        if (arrResult['success'] == true) {
                            EfyLib.swalAlert(arrResult['message'], '', '#24dd00', '#ffffff');
                            setTimeout(() => {
                                window.location.replace(baseUrl);
                            }, 2000)
                        } else {
                            EfyLib.swalAlert(arrResult['message'], '', '#ff9429', '#ffffff');
                        }
                    }
                });
            }
        // });
    </script>
@endsection

@section('content')
    <div class="container mt-3">
        <div>
            <h4>Đăng ký tài khoản</h4>
        </div>

        <div class="py-3" style="position: relative; width: 60%; left: 20%;">
            <form id="frm_sign_up">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="ho_ten" class="float-end required">Họ tên</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" placeholder="Nhập họ tên">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="cmnd" class="float-end required">Số CCCD/CMND</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="cmnd" name="cmnd"
                            placeholder="Nhập CCCD/CMND" onkeyup="validateCCCD(this)">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="email" class="float-end required">Email công vụ</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="example@haiduong.gov.vn" onblur="validateEmail(this)" value="@haiduong.gov.vn">
                        <span class="invalid-email">
                            Email không đúng định dạng. Phải kết thúc bằng @@haiduong.gov.vn
                        </span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="cap_don_vi" class="float-end required">Cấp đơn vị</label>
                    </div>
                    <div class="col-md-9">
                        <select class="form-select chzn-select" id="cap_don_vi" name="cap_don_vi"
                            onchange="changeUnitlevel(this)">
                            <option value="">-- Chọn cấp đơn vị --</option>
                            <option value="SO_NGANH">Sở - ngành</option>
                            <option value="QUAN_HUYEN">Quận - huyện</option>
                            <option value="PHUONG_XA">Phường - xã</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="don_vi" class="float-end required">Đơn vị</label>
                    </div>
                    <div class="col-md-9">
                        <select class="form-select chzn-select" id="don_vi" name="don_vi">
                        </select>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-primary" onclick="signUp()">Đăng ký</button>
                    <a type="button" class="btn btn-danger" href="{{ url()->previous() }}">Hủy</a>
                </div>
            </div>
        </div>
    </div>
@endsection
