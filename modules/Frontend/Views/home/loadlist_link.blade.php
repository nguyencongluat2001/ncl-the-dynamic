@extends('Frontend::layouts.index')
@section('body-client')
<script type="text/javascript" src="{{ URL::asset('dist/js/backend/client/JS_Home.js') }}"></script>
<script src='../assets/js/jquery.js'></script>
<style>

.content {
  padding: 0 18px;
  display: none;
  overflow: hidden;
}
.xemanh:hover{
  background: #37a956 !important;
  color:white !important;
}
</style>
<div class="panel panel-default panel-wrapper body-content" style="">
      <!-- Start Banner Hero -->
      <section class="bg-light w-100" style="padding: 0px 10px 0px 10px;">
        <div class="">
            <div class="row d-flex align-items-center">
                <div class="col-lg-12 text-start padding-index">
                <h2 class="card-title" style="font-weight: 600;padding-top:10px;font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 20px !important;color:#37a956"> Thông tin khách hàng</h2>
                    <div class="row g-lg-5">
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                            <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Họ tên: <span style="font-weight: 600;">{{$benhnhan['tenbn']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Giới tính: <span style="font-weight: 600;">{{$benhnhan['gioitinh']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Ngày sinh: <span style="font-weight: 600;">{{$benhnhan['namsinh']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Mã số PID:  <span style="font-weight: 600;">{{$benhnhan['mabn']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Mã BHYT : <span style="font-weight: 600;">{{$benhnhan['mabhyt']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Số điện thoại: <span style="font-weight: 600;">{{$benhnhan['dienthoai']}}</span></span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-12">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <span style="font-size: 15px !important;font-family: -apple-system, BlinkMacSystemFont !important;font-size: 18px !important;"> Địa chỉ: <span style="font-weight: 600;">{{$benhnhan['diachi']}}</span> </span>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                    </div>
                </div>
            </div>
        </div>
      </section>
      <!-- End Banner Hero -->
      @foreach ($chidinhct as $key => $data)
      <div style="padding:0px 15px 0px 20px">
          <div style="width: 98%;height: 100%;border-color: #000 !important;border: 1px solid #ccc !important;">
              <button type="button" class="collapsible" style="background:#37a956" onclick="JS_Home.openicon('{{$data['idchidinhct']}}')">
              <!-- <i class="fas fa-chevron-right" id="{{$data['idchidinhct']}}" value="1"></i> -->
                <div class="col-lg-12 text-start" >
                      <div class="row g-lg-5" style="display:flex">
                          <div style="width:70%">
                              <div style="border-radius: 5px;padding: 5px;font-size: 16px;font-weight: 700;color: white;" class="recent-work-content text-start">
                              <i class="fas fa-chevron-right" id="{{$data['idchidinhct']}}"></i>
                              <i class="fas fa-chevron-down" id="{{$data['idchidinhct']}}.1" style="display:none"></i>
                              <!-- <i class="fas fa-chevron-down"></i> -->
                                <span style="font-family: -apple-system, BlinkMacSystemFont !important"> {{$data['tendichvu']}}</span>
                              </div>
                          </div>
                          <div style="width:30%">
                            <div style="border-radius: 5px;padding: 5px;font-size: 16px;font-weight: 700;color: white;text-align: end;" class="recent-work-content text-start">
                                <span style="font-family: -apple-system, BlinkMacSystemFont !important">{{$data['ngaychidinh']}}</span>
                            </div>
                          </div>
                      </div>
                  </div>
              </button>
              @if($key == 0)
                <div class="content" style="display:block">
              @else
                <div class="content">
              @endif
                    <div style="color: rgba(0, 0, 0, .85);display: block;">
                      <div class="row d-flex align-items-center">
                        <div class="col-lg-12 text-start" >
                            <div class="row g-lg-5">
                                <div class="col-md-8" style="padding-left: 40px;padding-bottom: 25px;">
                                    <div style="border-radius: 5px;padding: 10px;font-size:14px !important;font-family: -apple-system, BlinkMacSystemFont !important" class="recent-work-content text-start text-dark">
                                      {!! $data['noidunghtml'] !!}
                                    </div>
                                    <div class="row g-lg-5">
                                      <!-- Start Recent Work -->
                                      <div class="col-md-12">
                                        <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Kết luận: <span style="font-weight: 500;">{{$data['ketluan']}}</span></div>
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-12">
                                        <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Khuyến nghị: <span style="font-weight: 500;">{{$data['denghi']}}</span></div>
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-12">
                                        <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Thời gian thực hiện: <span style="font-weight: 500;">{{$data['ngaychidinh']}}</span></div> 
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-6">
                                        <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Bác sĩ chỉ định: <span style="font-weight: 500;">{{$data['bschidinh']}}</span></div> 
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-6">
                                        <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Thời gian duyệt: <span style="font-weight: 500;">{{$data['ngayduyetketqua']}}</span></div> 
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-6">
                                          <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Bác sĩ duyệt: <span style="font-weight: 500;">{{$data['bsduyetketqua']}}</span> </div>
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-6">
                                          <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Bác sĩ đọc: <span style="font-weight: 500;">{{$data['bsdocketqua']}}</span> </div>
                                      </div>
                                      <!-- Start Recent Work -->
                                      <div class="col-md-6">
                                          <div style="padding: 5px;font-weight: 600;font-size: 14px;font-family: -apple-system, BlinkMacSystemFont !important">Kỹ thuật viên: <span style="font-weight: 500;">{{$data['ktvthuchien']}}</span></div> 
                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-1"> </div>
                                <div class="col-md-3">
                                  <div style="border-radius: 5px;padding-top:10px;text-align: end;padding-bottom: 40px" class="recent-work-content">
                                    <button style="background:#ffffff;color: #4169e1" type="button" onclick="JS_Home.openLink('{{$data['pacslink']}}')" class="btn xemanh">
                                      <span style="text-decoration: underline;font-size: 16px;">Xem ảnh</span>
                                    </button>
                                  </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
          </div>
      </div>
          
      @endforeach
</div>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
<script type="text/javascript">
    var baseUrl = "{{ url('') }}";
    var JS_Home = new JS_Home(baseUrl);
    $(document).ready(function($) {
        JS_Home.loadIndex(baseUrl);
    })
</script>
@endsection
