
<style>
    .form-wrapper,
    .checkbox {
        color: #ffffff;
    }

    .form-control {
        color: #000000;
        background:#ffffff !important;
    }
</style>
<link rel="stylesheet" href="../css/styleAdmin.css">

<div class="mt-2" style="background-image: url('/img/signin.jpeg');background-size: cover;background-position: center center;">
    <div style="background:#20364b94;">
        <div class="row justify-content-center" style="height:800px; ">
            <div class="col-md-7" style="padding-top:180px;">
                <!-- <div class="card" style="background:#20364b94;"> -->
                    <div class="wrapper" style="display: flex; justify-content: center;background: #0d1c2573;border-radius: 15px;margin: 20px;">
                        <form method="POST"  action="{{ route('system/login/checkLogin') }}"  autocomplete="off" style="padding: 30px;">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group" align="center">
                                <div class="col-md-12 mt-3 mb-3">
                                    <h3 class="text-uppercase" style="font-family: Serif;color:#ffd862;font-weight: 600;">Đăng nhập</h3>
                                </div>
                            </div>
                            
                            <div class="form-wrapper row {{!isset($data['email']) ? 'mb-3' : ''}}">
                                <label for="">Email <span class="request_star">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus placeholder="Email...">
                                @if(isset($data['email'])) <span style="color: red">{{$data['email']}}</span> @endif
                            </div>
                            <div class="form-wrapper row {{!isset($data['password']) ? 'mb-3' : ''}}">
                                <label for="">Mật khẩu <span class="request_star">*</span></label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="Mật khẩu">
                                @if(isset($data['password'])) <span style="color: red">{{$data['password']}}</span> @endif
                            </div>
                            <!-- <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Tôi chấp nhận Điều khoản sử dụng và Chính sách bảo mật. <span class="request_star">*</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </div> -->
                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #8b9ac5;">
                                {{ __('Quên mật khẩu?') }}
                            </a>
                            @endif
                            <div class="row mb-0">
                                @if(isset($data['message']))
                                <div class="col-md-12 text-center">
                                    <span style="color: red">{{$data['message']}}</span>
                                </div>
                                @endif
                                <div class="col-md-12 mb-3" style="display: flex;justify-content: space-between;">
                                    <button type="submit" class="btn btn-primary" style="background-color: #ffb600;color: white;">
                                        {{ __('Đăng nhập') }}
                                    </button>
                                </div>

                                <!-- <div class="col-md-12">
                                    <span class="text-white">Bạn chưa có tài khoản? <i><a style="color:#84ffda">Đăng ký ngay</a></i></span>
                                </div> -->
                            </div>
                        </form>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>