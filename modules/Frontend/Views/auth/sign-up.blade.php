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
				<!-- <div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>Bạn có săn sàng để đăng nhập để mua sắm?</h4>
							<p>Có những tiến bộ đang được thực hiện hàng ngày trong khoa học và công nghệ, và một ví dụ điển hình cho điều này là</p>
							<a class="button button-account" href="login">Đăng nhập</a>
						</div>
					</div>
				</div> -->
				<div class="col-lg-12">
					<div class="login_form_inner register_form_inner">
						<h4>Tạo tài khoản</h4><br>
						<form class="row login_form" id="frm_sign_up" >
						<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
						   <div class="row col-md-12">
								<div class="col-md-6 form-group">
								     <input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Họ và tên'">
								</div>
								<div class="col-md-6 form-group">
								     <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Số điện thoại'">
								</div>
							</div>
							<div class="row col-md-12">
								<div class="col-md-6 form-group">
								    <input type="text" class="form-control" id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
								</div>
								<div class="col-md-6"  style="padding-top:10px">
									<input type="radio" value="1" name="sex" id="sex"/>  <span style="padding-left:5px" >Nam</span>&emsp;
									<input  type="radio" value="2" name="sex" id="sex"/> <span style="padding-left:5px" >Nữ</span>	
								</div>
							</div>
							<div class="row col-md-12">
								<div class="form-input col-md-4 padding-style">
									<select onchange="JS_Auth.getHuyen(this.value)" class="form-control input-sm chzn-select" name="code_tinh" id="code_tinh">
										<option value="">--Chọn tỉnh thành--</option>
										@foreach($tinh as $key => $value)
										<option value="{{$value->code_tinh}}">{{$value->name}}</option>
										@endforeach
									</select>
								</div>
								<div class="form-input col-md-4 padding-style">
								    <select onchange="JS_Auth.getXa(this.value)" class="form-control input-sm chzn-select" name="code_huyen" id="code_huyen">
										<option value="">--Chọn quận huyện--</option>
									</select>
								</div>
								<div class="form-input col-md-4 padding-style">
									<select class="form-control input-sm chzn-select" name="code_xa" id="code_xa">
										<option value="">--Chọn phường xã--</option>
									</select>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="address" name="address" placeholder="Địa chỉ chi tiết (Số nhà - đường - xóm ,...)" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Địa chỉ chi tiết(Số nhà - thôn - xóm ,...)'">
                            </div>
                            <div class="col-md-12 form-group">
                                                <input type="text" class="form-control" id="password" name="password" placeholder="Mật khẩu" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mật khẩu'">
                            </div>
                            <div class="col-md-12 form-group">
								<input type="text" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Nhập lại mật khẩu'">
							</div>
							<!-- <div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" id="f-option2" name="selector">
									<label for="f-option2">Keep me logged in</label>
								</div>
							</div> -->
							<div class="col-md-12 form-group">
								<button type="button" onclick="JS_Auth.signUp()"class="button button-register w-100">Đăng ký</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->
@endsection
