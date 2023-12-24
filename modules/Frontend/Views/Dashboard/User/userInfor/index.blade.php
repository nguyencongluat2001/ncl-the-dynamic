@extends('system.layouts_Backend.index')
@section('body')
<script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_User_info.js') }}"></script>

<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <form action="" method="POST" id="frmUsersInfo_index">
    @csrf
    <form id="frmView"  role="form" action="" method="POST">

      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <input type="hidden" name="id" id="id" value="{{$data['id']}}">
                      @if(!empty($data) && $_SESSION["email"] == $data['email'] || $_SESSION["role"] == 'ADMIN')
                      <span class="pt-3">
                        <button class="btn btn-primary btn-sm ms-auto" type="button" id='btn_changePass'>
                          Đổi mật khẩu
                        </button>
                      </span>
                      @endif
                      {{--
                          <h6 style="padding-left:10%" class="mb-0">Sáng/tối &nbsp;&nbsp;</h6></h6>
                          <div  class="form-check form-switch" id="btn_checkbox">
                              <input class="form-check-input mt-1" type="checkbox" name="is_checkbox" id="dark-version"{{($_SESSION["color_view"] == '1') ? 'checked' : ''}}/>
                          </div>
                      </div> --}}
                  <!-- </div> -->
                  <!-- <button class="btn btn-primary btn-sm ms-auto">Đổi mật khẩu</button> -->
                </div>
              </div>
              <div class="card-body">
                <p class="text-uppercase text-sm">Thông tin người dùng</p>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Tên</p>
                      <input disabled  class="form-control" type="text" value="{{$data['name']}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Địa chỉ Email</p>
                      <input disabled class="form-control" type="email" value="{{$data['email']}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Ngày sinh</p>
                      <input disabled class="form-control" type="date" value="{{$data['dateBirth']}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Số điện thoại</p>
                      <input disabled class="form-control" type="text" value="{{$data['phone']}}">
                    </div>
                  </div>
                </div>
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm">Thông tin liên lạc</p>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Địa chỉ</p>
                      <input disabled class="form-control" type="text" value="{{$data['address']}}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Công ty</p>
                      <input disabled class="form-control" type="text" value="{{$data['company']}}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Chức vụ</p>
                      <input disabled class="form-control" type="text" value="{{$data['position']}}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Gia nhập ngày</p>
                      <input disabled class="form-control" type="date" value="{{$data['date_join']}}">
                    </div>
                  </div>
                </div>
                <!-- <hr class="horizontal dark">
                <p class="text-uppercase text-sm">About me</p>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">About me</label>
                      <input disabled class="form-control" type="text" value="A beautiful Dashboard for Bootstrap 5. It is Free and Open Source.">
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-profile">
              <img src="../assets/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
              <div class="row justify-content-center">
                <div class="col-4 col-lg-4 order-lg-2">
                  <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                    <a href="javascript:;">
                      <img src="{{url('/file-image/avatar/')}}/{{$data['avatar']}}" style="height: 150px;width: 150px;object-fit: cover;border-radius:50%" >
                    </a>
                  </div>
                </div>
              </div>
              <!-- <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                <div class="d-flex justify-content-between">
                  <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                  <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i class="ni ni-collection"></i></a>
                  <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                  <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i class="ni ni-email-83"></i></a>
                </div>
              </div> -->
              <div class="card-body pt-0">
                <!-- <div class="row">
                  <div class="col">
                    <div class="d-flex justify-content-center">
                      <div class="d-grid text-center">
                        <span class="text-lg font-weight-bolder">22</span>
                        <span class="text-sm opacity-8">Friends</span>
                      </div>
                      <div class="d-grid text-center mx-4">
                        <span class="text-lg font-weight-bolder">10</span>
                        <span class="text-sm opacity-8">Photos</span>
                      </div>
                      <div class="d-grid text-center">
                        <span class="text-lg font-weight-bolder">89</span>
                        <span class="text-sm opacity-8">Comments</span>
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="text-center mt-4">
                  <h5>
                   {{$data['name']}}
                  </h5>
                  <div class="h6 font-weight-300">
                    <i class="ni location_pin mr-2"></i> ID nhân sự: <span style="color:red">{{$data['id_personnel']}}</span>
                  </div>
                  <div class="h6 font-weight-300">
                    <i class="ni location_pin mr-2"></i> Link giới thiệu: <span style="color:red">https://fintopdata.vn/dangky/{{$data['id_personnel']}}</span>
                  </div>
                  <!-- <div class="h6 mt-4">
                    <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                  </div>
                  <div>
                    <i class="ni education_hat mr-2"></i>University of Computer Science
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="modal fade" id="editmodal" role="dialog"></div>

    <div class="modal " id="editPassmodal" role="dialog" style=" width: 100%;height: 100%;background: #0000007d; background-repeat:no-repeat;background-size: cover;"></div>

    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = '{{ url('') }}';
        var JS_User_info = new JS_User_info(baseUrl, 'system', 'userInfo');
        $(document).ready(function($) {
            JS_User_info.loadIndex(baseUrl);
        })
    </script>
@endsection

