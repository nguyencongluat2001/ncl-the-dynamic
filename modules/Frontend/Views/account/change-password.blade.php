@extends('Frontend::account.layout.index')

@section('script-child')
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    EfyLib.loadFileJsCss(arrJsCss);

    var jsChangePassword = new ChangePassword('{{ url('') }}', 'tai-khoan');
    jQuery(document).ready(function($) {
        jsChangePassword.loadIndex();
    })
</script>
@endsection

@section('content-child')
<form id="frm_info">

    <div class="container">
        <h3 class="account-title">Đổi mật khẩu</h3>

        <div class="mt-3">
            <div class="row">
                <div class="col-md-4 text-md-end">
                    <label for="password" class="required">Mật khẩu</label>
                </div>
                <div class="col-md-8">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu hiện tại">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 text-md-end">
                    <label for="new_password" class="required">Mật khẩu mới</label>
                </div>
                <div class="col-md-8">
                    <input type="password" class="form-control" id="new_password" name="new_password"
                        placeholder="Mật khẩu mới">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 text-md-end">
                    <label for="re_password" class="required">Nhập lại mật khẩu mới</label>
                </div>
                <div class="col-md-8">
                    <input type="password" class="form-control" id="re_password" name="re_password"
                        placeholder="Mật khẩu mới">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col text-end">
                    <button type="button" role="button" id="btn_update" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
