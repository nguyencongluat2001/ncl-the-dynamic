@php
    use Modules\Core\Ncl\FunctionHelper;
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
                    {{-- Tên đợt --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label align="left" class="radio ms-0 col-md-3 text-right control-label required">Tên câu hỏi</label>
                                <textarea class="form-control" type="text" rows="3" cols="30" name="ten_cau_hoi" id="ten_cau_hoi" >{{!empty($datas->ten_cau_hoi)?$datas->ten_cau_hoi:''}}</textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Đáp án A</label>
                                <div class="col-md-10">
                                    <!-- <input class="form-control input-md" type="text" id="dap_an_a" name="dap_an_a"
                                        value="{{ $datas->dap_an_a ?? '' }}" > -->
                                    <textarea class="form-control" type="text" rows="3"  name="dap_an_a" id="dap_an_a" >{{ $datas->dap_an_a ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Đáp án B</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" type="text" rows="3"  name="dap_an_b" id="dap_an_b" >{{ $datas->dap_an_b ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Đáp án C</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" type="text" rows="3"  name="dap_an_c" id="dap_an_c" >{{ $datas->dap_an_c ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Đáp án D</label>
                                <div class="col-md-10">
                                <textarea class="form-control" type="text" rows="3"  name="dap_an_d" id="dap_an_d" >{{ $datas->dap_an_d ?? '' }}</textarea>

                                    <!-- <input class="form-control input-md" type="text" id="dap_an_d" name="dap_an_d"
                                        value="{{ $datas->dap_an_d ?? '' }}"> -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hội thi năm-->
                        <div class="form-group col-md-12" data-form="add">
                            <div style="display:flex">
                                <label style="padding-top:1%;width:130px" align="left" class="radio ms-0 text-right control-label required">Đáp án đúng</label>
                                <div class="col-md-5">
                                    <select class="form-control input-md chzn-select" name="dap_an_dung"
                                        id="dap_an_dung">
                                        <option value=''>-- Chọn Đáp án --</option>
                                        <option 
                                            @if(isset($datas->dap_an_dung) && $datas->dap_an_dung == 'A')
                                            selected
                                            @else
                                            @endif
                                            value='A'>Đáp án A
                                        </option>
                                        <option 
                                            @if(isset($datas->dap_an_dung) && $datas->dap_an_dung == 'B')
                                            selected
                                            @else
                                            @endif
                                            value='B'>Đáp án B
                                        </option>
                                        <option 
                                            @if(isset($datas->dap_an_dung) && $datas->dap_an_dung == 'C')
                                            selected
                                            @else
                                            @endif
                                            value='C'>Đáp án C
                                        </option>
                                        <option 
                                            @if(isset($datas->dap_an_dung) && $datas->dap_an_dung == 'D')
                                            selected
                                            @else
                                            @endif
                                            value='D'>Đáp án D
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label required">Thứ tự</label>
                                <div class="col-md-2">
                                    <input class="form-control input-md" type="text" id="thu_tu" name="thu_tu"
                                        value="{{isset($datas->thu_tu) ? $datas->thu_tu : $datas['thu_tu']}}">
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