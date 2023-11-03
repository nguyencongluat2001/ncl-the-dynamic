@extends('Frontend::layouts.index')


@section('body-client')
      <!--================Login Box Area =================-->
	<section class="login_box_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>Bạn có săn sàng để đăng nhập để mua sắm?</h4>
							<p>Có những tiến bộ đang được thực hiện hàng ngày trong khoa học và công nghệ, và một ví dụ điển hình cho điều này là</p>
							<a class="button button-account" href="login.html">Đăng nhập</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner register_form_inner">
						<h3>Tạo tài khoản</h3>
						<form class="row login_form" action="#/" id="register_form" >
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Họ và tên'">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="email" name="email" placeholder="Địa chỉ Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Địa chỉ email'">
                            </div>
                            <div class="col-md-12 form-group">
								<input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Số điện thoại'">
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
								<button type="submit" value="submit" class="button button-register w-100">Đăng ký</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->
@endsection
