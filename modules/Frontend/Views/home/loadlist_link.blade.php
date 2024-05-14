@extends('Frontend::layouts.index')
@section('body-client')
<script type="text/javascript" src="{{ URL::asset('dist/js/backend/client/JS_Home.js') }}"></script>
<script src='../assets/js/jquery.js'></script>
<style>
.collapsible {
  color: white;
  cursor: pointer;
  width: 100%;
  height: 36px;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.content {
  padding: 0 18px;
  display: none;
  overflow: hidden;
}
</style>



<div class="panel panel-default panel-wrapper body-content">
      <!-- Start Banner Hero -->
      <section class="bg-light w-100">
        <div class="">
            <div class="row d-flex align-items-center">
                <div class="col-lg-12 text-start">
                <h2 class="card-title" style="font-weight: 600;padding-left:10px;padding-top:10px;font-size: 15px !important;font-family: auto;font-size: 20px !important;color:#37a956"> Thông tin khách hàng</h2>

                    <div class="row g-lg-5">
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                            <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Họ tên: <span style="font-weight: 600;">{{$benhnhan['tenbn']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Giới tính: <span style="font-weight: 600;">{{$benhnhan['gioitinh']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Ngày sinh: <span style="font-weight: 600;">{{$benhnhan['namsinh']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Mã số PID:  <span style="font-weight: 600;">{{$benhnhan['mabn']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                        <!-- Start Recent Work -->
                        <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Mã BHYT : <span style="font-weight: 600;">{{$benhnhan['mabhyt']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-4">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Số điện thoại: <span style="font-weight: 600;">{{$benhnhan['dienthoai']}}</span></h3>
                          </div>
                        </div>
                        <!-- End Recent Work -->
                          <!-- Start Recent Work -->
                          <div class="col-md-12">
                          <div style="border-radius: 5px" class="recent-work-content text-start text-dark">
                              <h3 style="padding:10px;font-size: 15px !important;font-family: auto;font-size: 18px !important;"> Địa chỉ: <span style="font-weight: 600;">{{$benhnhan['diachi']}}</span> </h3>
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
      <div style="width: 100%;height: 100%;border-color: #000 !important;border: 1px solid #ccc !important;">

      <button type="button" class="collapsible" style="background:#37a956">
        <div class="col-lg-12 text-start" >
              <div class="row g-lg-5" style="display:flex">
                  <div style="width:70%">
                      <div style="border-radius: 5px;padding: 5px;font-size: 16px;font-weight: 700;color: white;" class="recent-work-content text-start">
                      <i class="fas fa-chevron-right"></i>
                      <!-- <i class="fas fa-chevron-down"></i> -->
                        <span> {{$data['tendichvu']}}</span>
                      </div>
                  </div>
                  <div style="width:30%">
                    <div style="border-radius: 5px;padding: 5px;font-size: 16px;font-weight: 700;color: white;" class="recent-work-content text-start">
                        <span>{{$data['ngaychidinh']}}</span>
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
                            <div class="col-md-6" style="padding-left: 40px;padding-bottom: 25px;">
                                <div style="border-radius: 5px;padding: 10px;" class="recent-work-content text-start text-dark">
                                  {!! $data['noidunghtml'] !!}
                                </div>
                                <div>
                                  <div style="padding: 5px;">Kết luận: <span style="font-weight: 600;">{{$data['ketluan']}}</span></div>
                                  <div style="padding: 5px;">Khuyến nghị: <span style="font-weight: 600;">{{$data['denghi']}}</span></div>
                                  <div style="padding: 5px;">Thời gian thực hiện: <span style="font-weight: 600;">{{$data['ngaychidinh']}}</span></div> 
                                  <div style="padding: 5px;">Bác sĩ chỉ định: <span style="font-weight: 600;">{{$data['bschidinh']}}</span></div> 
                                  <div style="padding: 5px;">Thời gian duyệt: <span style="font-weight: 600;">{{$data['ngayduyetketqua']}}</span></div> 
                                  <div style="padding: 5px;">Bác sĩ duyệt: <span style="font-weight: 600;">{{$data['bsduyetketqua']}}</span> </div>
                                  <div style="padding: 5px;">Bác sĩ đọc: <span style="font-weight: 600;">{{$data['bsdocketqua']}}</span> </div>
                                  <div style="padding: 5px;">Kỹ thuật viên: <span style="font-weight: 600;">{{$data['ktvthuchien']}}</span></div> 
                                </div>
                            </div>
                            <div class="col-md-3"> </div>
                            <div class="col-md-3">
                              <div style="border-radius: 5px;" class="recent-work-content">
                                <dx-button style="opacity: 1 !important;" (onClick)="xemketqua()" id="define" icon="fa fa-sitemap" 
                                  text="Xem kết quả thực tế ảo">
                                </dx-button>
                                <dx-button style="opacity: 1 !important;" (onClick)="xemanh()" id="define"icon="fa fa-user" 
                                  text="Xem ảnh">
                                </dx-button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div lass="col-lg-12" style="padding-left:40px">
                        <button type="button" onclick="JS_Home.openLink('{{$data['pacslink']}}')" class="btn">
                          <span style="text-decoration: underline;font-size: 16px;color: #4169e1">Xem ảnh</span>
                        </button>
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
