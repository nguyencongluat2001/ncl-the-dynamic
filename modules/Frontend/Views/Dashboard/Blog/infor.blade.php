<form id="frmAdd"  role="form" action="" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{!empty($data['id'])?$data['id']:''}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card"> 
            <div class="modal-header">
                <h5 class="modal-title">Thông tin chi tiết bài viết</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
                    X
                </button>
            </div>
              <div class="card-body">
                <!-- <p class="text-uppercase text-sm">Thông tin cơ bản</p> -->
                <div class="row">
                 
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Người tạo</p>
                      <input class="form-control" type="text" value="{{$dataInfor['users_name']}}" disabled />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Ngày tạo bài viết</p>
                      <input class="form-control" type="text" value="{{$dataInfor['created_at']}}" disabled />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Thể loại</p>
                            <input class="form-control" type="text" value="{{$dataInfor['name_category']}}" disabled/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Trạng thái</p>
                      <input class="form-control" type="text" value="{{$dataInfor['status']}}" disabled />
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Tên bài viết</p>
                      <input class="form-control" type="text" value="{{$dataInfor['title']}}" disabled/>
                    </div>
                  </div>
                 
                <!-- <hr class="horizontal dark"> -->
                <!-- <p class="text-uppercase text-sm">Thông tin liên lạc</p> -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <p for="example-text-input" class="form-control-label">Nội dung</p>
                      <textarea style="height:150px" class="form-control" disabled>{{ $dataInfor['decision'] }}</textarea>
                    </div>
                  </div>
                </div>
                <div class="row projects gx-lg-5">
                <!-- Ảnh -->
                <div class="row projects gx-lg-5">
                <?php $images = $dataInfor['image'] ?>
                <p>
                    Ảnh bài viết
                </p>
                <div class="row projects gx-lg-5" style="padding-left:20%" align="center">
                    @foreach ($images as $value)
                            <span class="col-sm-6 col-lg-6 text-decoration-none project marketing social business">

                            <!-- <div class="service-work overflow-hidden card mb-5 mx-5 mt-5 m-sm-0" style="padding-top:10px"> -->
                                <div class="card-img-top" style="width:350px;margin-top:30px;padding-top:20px">
                                <img  src="{{url('/file-image-client/blogs/')}}/{{$value['name_image']}}" alt="Image"
                                     style="height: 150px;width: 150px;object-fit: cover;">
                                </div>
                            <!-- </div> -->
                        </span>
                    @endforeach
                </div>
                       
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
                        Đóng
                    </button>
                </div>
                </div>
              </div>
        </div>
    </div>
</form>