<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('resources/assets/fontawesome-free-5.15.4-web/css/all.min.css') }}">
    <!-- Bootstrap 5.2.3 + Soft UI -->
    <link id="pagestyle" href="{{ asset('resources/assets/soft-ui-dashboard/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>
</head>

<body>

    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form id="frm_login" action="{{ url('system/login/checklogin') }}" method="POST" role="form">
                        @csrf
                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-uppercase">Quản trị hệ thống</p>
                        </div>

                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <label class="form-label required" for="username">Tài khoản</label>
                            <input type="text" id="username" name="username" class="form-control form-control-lg"
                                placeholder="Enter a username" required />
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <label class="form-label required" for="password">Mật khẩu</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg"
                                placeholder="Enter password" required />
                            @if (isset($message) && $message !== '')
                                <p for="password" class="small fw-bold mt-2 pt-1 mb-0" style="color: red">
                                    {{ $message }}</p>
                            @endif
                        </div>

                        {{-- <div class="d-flex justify-content-between align-items-center">
                            <!-- Checkbox -->
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                                <label class="form-check-label" for="form2Example3">
                                    Remember me
                                </label>
                            </div>
                            <a href="#!" class="text-body">Forgot password?</a>
                        </div> --}}

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="button" id="btn_submit" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập</button>
                            {{-- <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!"
                                    class="link-danger">Register</a></p> --}}
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        {{-- <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © 2020. All rights reserved.
            </div>

            <!-- Right -->
            <div>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#!" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#!" class="text-white">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div> --}}
    </section>

    <script src="{{ asset('resources/js/assets/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('resources/js/assets/popper.min.js') }}"></script>
    <script src="{{ asset('resources/assets/bootstrap-5.2.3-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('resources/js/assets/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('resources/js/system/Login/JS_Login.js') }}"></script>

</body>

</html>
