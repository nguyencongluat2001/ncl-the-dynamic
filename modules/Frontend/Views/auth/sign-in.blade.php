@extends('Frontend::layouts.index')

<!-- @section('script')
    <script type="text/javascript">

        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib.loadFileJsCss(arrJsCss);

        var JS_Auth = new Auth('{{ url('') }}', '');
        jQuery(document).ready(function($) {
            JS_Auth.loadIndex();
        })
    </script>
@endsection -->
<script type="text/javascript" src="{{ URL::asset('js\frontend\login\auth.js') }}"></script>

@section('body-client')
  <!--================Login Box Area =================-->
	<section class="login_box_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>Mời vào trang web của chúng tôi?</h4>
							<p>Có những tiến bộ đang được thực hiện hàng ngày trong khoa học và công nghệ, và một ví dụ điển hình cho điều này là</p>
							<a class="button button-account" style="background:#ffaf00;" href="register">Tạo tài khoản</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h4>ĐĂNG NHẬP</h4><br>
						
						<form class="row login_form"  action="" id="frm_sign_in" >
						    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại hoặc email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Số điện thoại hoặc email'">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="password" name="password" placeholder="Mật khẩu" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mật khẩu'">
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" id="f-option2" name="selector">
									<label for="f-option2">Đồng ý chấp nhận điều khoản bảo mật của chúng tôi!</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="button" class="button button-login w-100" onclick="JS_Auth.signIn()">Đăng nhập</button>
								<a href="#">Quên mật khẩu?</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->
@endsection
