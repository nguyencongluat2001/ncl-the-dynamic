@extends('Frontend::layouts.index')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        EfyLib.loadFileJsCss(arrJsCss);

        var forgot = new ForgotPassword('{{ url('') }}', 'quen-mat-khau');
        jQuery(document).ready(function($) {
            forgot.loadIndex();
        })
    </script>
@endsection

@section('content')
    <div class="container mt-3">
        <div>
            <h4>Quên mật khẩu</h4>
        </div>
        <form id="frm_forgotPassword">
            <div class="py-3" style="position: relative; width: 60%; left: 20%;">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="email" class="float-end required">Email công vụ</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="example@haiduong.gov.vn">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="captcha" class="float-end required">Mã xác nhận</label>
                    </div>
                    <div class="col-md-9">
                        <div style="display: flex">
                            <div style="flex: 0 0 69%; max-width: 69%; width: 69%;">
                                <input type="text" class="form-control" id="captcha" name="captch"
                                    placeholder="Nhập mã xác nhận">
                            </div>
                            <div class="input-group ms-1" title="Mã xác nhận"
                                style="flex: 0 0 26%; position: relative;height: 38px;border-radius:0.25rem;background-color: #d5a9a9;">
                                <div id="random_captcha" class="ml-3"></div>
                            </div>
                            <span style="display: flex; align-items: center; height: 36px; position: absolute; right: 0px;">
                                <a class="cursor-pointer" style="color: black" id="btn_refresh_captcha"
                                    title="Làm mới mã xác nhận">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </span>
                        </div>
                        <span id="msg_err" style="color: red; font-style: italic; display: none">Mã xác nhận không chính xác</span>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 text-end">
                <button type="button" id="btn_forgot" class="btn btn-primary">Lấy lại mật khẩu</button>
            </div>
        </div>
    </div>
    </div>
@endsection
