<style>
    .cke_reset{
        height:700px !important;
    }
</style>
<div class="modal-dialog modal-fullscreen" style="height: 1100px;">
    <div class="modal-content card">
        <div class="modal-header">
            <h5 class="modal-title">Cập nhật bài viết</h5>
            <span type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
                X
            </span>
        </div>
        <div class="card-body" >
            <form id="frmAdd" role="form" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="id" value="{{!empty($data['id'])?$data['id']:''}}">
                <div class="row">
                   <div class="col-md-8">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p for="example-text-input" class="form-control-label required">Tiêu đề</p>
                                <input class="form-control" type="text" value="{{!empty($data['title'])?$data['title']:''}}" name="title" id="title" placeholder="Nhập tiêu đề..." />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p for="example-text-input" class="form-control-label">Nội dung</p>
                                <textarea class="form-control" type="text" name="decision" id="decision" placeholder="Nhập nội dung...">{{!empty($data['decision'])?$data['decision']:''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p for="example-text-input" class="form-control-label required">Thể loại</p>
                                <select class="form-control input-sm chzn-select" name="code_category" id="code_category">
                                    <option value=''>-- Chọn thể loại --</option>
                                    @foreach($data['category'] as $item)
                                    <option @if((isset($data['code']) && $data['code'] == $item['code_category']) || 
                                            (isset($data['code_category']) && $data['code_category'] == $item['code_category'])) selected @endif 
                                            value="{{$item['code_category']}}">{{$item['name_category']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(isset($_SESSION['role']) && ($_SESSION['role'] == 'ADMIN' || $_SESSION['role'] == 'MANAGE' ||
                             $_SESSION['role'] == 'CV_ADMIN' || $_SESSION['role'] == 'CV_ADMIN,SALE_ADMIN' || $_SESSION['role'] == 'CV_ADMIN,SALE_BASIC'))
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p for="example-text-input" class="form-control-label required">Loại bài viết</p>
                                    <select class="form-control input-sm chzn-select" name="type_blog" id="type_blog">
                                        <option @if((isset($data['type_blog']) && $data['type_blog'] == 'BASIC')) selected @endif value='BASIC'>Basic</option>
                                        <option @if((isset($data['type_blog']) && $data['type_blog'] == 'VIP')) selected @endif  value='VIP'>Vip</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                           
                            <div class="col-md-6">
                                <!-- <span class="col-md-3 control-label required">Chọn ảnh đại diện</span><br> -->
                                <label for="upload_image" class="label-upload">Chọn ảnh</label>
                                <input type="file" hidden name="upload_image" id="upload_image" onchange="readURL(this)">
                                <br>
                                @if(!empty($data['image']))
                                <img id="show_img" src="{{url('/file-image-client/blogs/')}}/{{$data['image'][0]->name_image ?? ''}}" alt="Image" style="width:150px">
                                @else
                                <img id="show_img" hidden alt="Image" style="width:150px">
                                @endif
                            </div>
                            {{-- trạng thái --}}
                            <div class="col-md-6">
                                <div class="row form-group" id="div_hinhthucgiai">
                                    <span class="control-label">Trạng thái</span><br>
                                    <div>
                                        <input type="checkbox" name="status" id="status" {{(isset($data['status']) && $data['status'] == '1') ? 'checked' : ''}} />
                                        <label for="status">Hoạt động</label> <br>
                                    </div>
                                </div>
                            </div>
                            <button id='btn_create' class="btn btn-primary btn-sm" type="button">Cập nhật</button>
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">Đóng</button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        
                    </div> -->
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#show_img').attr('src', e.target.result).width(150);
            };
            $("#show_img").removeAttr('hidden');

            reader.readAsDataURL(input.files[0]);
        }
    }
    <?php
        $url = url('system/blog/uploadFileCK?_token=') . csrf_token();
    ?>
    var url = '{{ $url }}';
    CKEDITOR.replace('decision', {
        filebrowserUploadUrl: url,
        filebrowserUploadMethod: 'form',
    });
</script>