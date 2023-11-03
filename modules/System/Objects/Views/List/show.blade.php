@php
    use Modules\Core\Ncl\FunctionHelper;
@endphp
@php
    use Modules\System\ListType\Models\ListModel;
@endphp
<form id="frmAdd" role="form" action="" method="POST">
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{isset($datas->id) ? $datas->id : ''}}">
    <input type="hidden" name="dot_thi_id" id="dot_thi_id" value="{{isset($dot_thi_id) ? $dot_thi_id : ''}}">

    <div class="modal-dialog modal-lg">
        <div class="modal-content  card">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết câu hỏi</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                   
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Họ và tên</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" type="text" id="ho_ten" name="ho_ten"
                                        value="{{ $datas->ho_ten ?? '' }}" >
                                </div>

                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Email</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" type="text" id="email" name="email"
                                        value="{{ $datas->email ?? '' }}" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Chứng minh nhân dân</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" type="text" id="cmnd" name="cmnd"
                                        value="{{ $datas->cmnd ?? '' }}" >
                                </div>

                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Đơn vị</label>
                                <div class="col-md-4">
                                <?php $donvi = ListModel::where('code',$datas->don_vi)->first(); ?>
                                <input class="form-control input-md" type="text" id="don_vi" name="don_vi"
                                        value="{{  !empty($donvi->name)?$donvi->name:'' }}" >
                                </div>
                                </div>
                            </div>
                        </div>
                        
                      
                        {{-- trạng thái --}}
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Trạng thái</label>
                                <div style="padding-top:1%" class="col-md-5">
                                    <input type="checkbox" id="trang_thai" name="trang_thai"  
                                    @if(isset($datas->trang_thai) && $datas->trang_thai == 1)
                                    checked
                                    @elseif(!isset($datas->trang_thai))
                                    checked
                                    @else
                                    @endif
                                    ">
                                    Hoạt động
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .radio-container label.error {
        float: right;
    }
</style>
<!-- <script>
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy'
    });
</script> -->
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
    CKEDITOR.replace('ten_cau_hoi', {
        filebrowserBrowseUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserUploadUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserImageBrowseUrl: 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
    });
</script>