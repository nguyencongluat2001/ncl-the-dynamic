@extends('Frontend::layouts.index')
@section('body-client')
  <!--================Login Box Area =================-->
	<section class="login_box_area">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_box_img" style="padding-top:150px">
						<div class="login_form_inner">
							<center>
							<div class="col-lg-6" style="background: white;padding: 30px;border-radius: 10px;">
								<div style="color: #4700ff;font-weight: 700;font-size: 22px;font-family:serif">CỔNG TRẢ KẾT QUẢ <br> CHẨN ĐOÁN HÌNH ẢNH</div>
								<form class="row login_form"  action="" id="frm_sign_in" >
									<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
									<div class="col-md-12 form-group">
										<input type="text" class="form-control" id="username" name="username" placeholder="Mã bệnh nhân" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mã bệnh nhân'">
									</div>
									<div class="col-md-12 form-group">
										<input type="text" class="form-control" id="password" name="password" placeholder="Mật khẩu" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Mật khẩu'">
									</div>
									<div class="col-md-12 form-group">
										<button style="background:#37a956" type="button" class="button button-login w-100" onclick="JS_Home.signIn()">Tra cứu</button>
									</div>
								</form>
							</div>	
							</center>
							
						</div>
					</div>
				</div>
			</div>
	</section>
<script type="text/javascript" src="{{ URL::asset('dist/js/backend/client/JS_Home.js') }}"></script>
<script src='../assets/js/jquery.js'></script>
<script type="text/javascript">
    NclLib.menuActive('.link-bloodtest');
    var baseUrl = "{{ url('') }}";
    var JS_Home = new JS_Home(baseUrl);
    $(document).ready(function($) {
        JS_Home.loadIndex(baseUrl);
    })
</script>
	<!--================End Login Box Area =================-->
@endsection
