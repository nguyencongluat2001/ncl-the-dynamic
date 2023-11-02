@extends('Frontend::layouts.index')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        EfyLib.loadFileJsCss(arrJsCss);

        var JS_Auth = new Auth('{{ url('') }}', 'dang-nhap');
        jQuery(document).ready(function($) {
            JS_Auth.loadIndex();
        })
    </script>
@endsection

@section('content')
    <div class="container mt-3">
        <div>
            <h4>Đăng nhập</h4>
        </div>

        <div class="py-3" style="position: relative; width: 60%; left: 20%;">
            <form action="{{ url('dang-nhap') }}" id="frm_sign_in">
                <input type="hidden" name="next_url" value="{{ $nextUrl }}">

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
                        <label for="password" class="float-end required">Mật khẩu</label>
                    </div>
                    <div class="col-md-9">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu">
                        <span id="msg_error" class="mt-1 fst-italic hide" style="color: red;">Tài khoản không chính xác</span>
                    </div>
                </div>

            </form>

            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-primary" onclick="JS_Auth.signIn()">Đăng nhập</button>
                </div>
            </div>

            <div style="display: flex; justify-content: space-evenly; align-items: center;">
                <a href="{{ url('quen-mat-khau') }}">Quên mật khẩu?</a>
                <a href="{{ url('dang-ky') }}">Đăng ký tài khoản</a>
            </div>
        </div>
    </div>
@endsection
