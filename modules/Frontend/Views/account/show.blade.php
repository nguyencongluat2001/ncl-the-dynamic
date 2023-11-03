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
                    <h5 class="modal-title">Chi tiết bài thi</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                        X
                    </button>
                </div>
                <div class="modal-body">
                <h6><span>I. THÔNG TIN ĐỐI TƯỢNG</span></h6>

                    <div class="row" style="font-size:14px">
                    {{-- Tên đợt --}}
                        <center>
                        <div class="form-group col-md-10" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Họ và tên</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" type="text" id="doi_tuong_ho_ten" name="doi_tuong_ho_ten"
                                        value="{{ $datas->doi_tuong_ho_ten ?? '' }}" >
                                </div>
                                
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Địa chỉ Email</label>
                                <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="doi_tuong_email" name="doi_tuong_email"
                                        value="{{ $datas->doi_tuong_email ?? '' }}" >
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group col-md-10" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Thời điểm nộp bài</label>
                                <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="thoi_diem_nop_bai" name="thoi_diem_nop_bai"
                                        value="{{ date('H:i:s d-m-Y', strtotime($datas->thoi_diem_nop_bai)) }}" >
                                </div>

                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Thời gian làm bài</label>
                                <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="thoi_gian_lam_bai" name="thoi_gian_lam_bai"
                                        value="{{ $datas->thoi_gian_lam_bai ?? '' }} giây" >
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group col-md-10" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Điểm đạt được</label>
                                <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="diem" name="diem"
                                        value="{{ $datas->diem ?? '' }} - ({{ $datas->so_dap_an_dung ?? '' }}/10 câu đúng)" >
                                </div>

                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Số người dự đoán</label>
                                <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="du_doan_so_nguoi" name="du_doan_so_nguoi"
                                        value="{{ $datas->du_doan_so_nguoi ?? '' }}" >
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group col-md-10" data-form="add">
                            <div class="row">
                                <label style="padding-top:1%" align="left" class="radio ms-0 col-md-2 text-right control-label">Đơn vị</label>
                                <div class="col-md-10">
                                <?php $donvi = ListModel::where('code',$datas->doi_tuong_don_vi)->first(); ?>
                                <input class="form-control input-md" type="text" id="doi_tuong_don_vi" name="doi_tuong_don_vi"
                                        value="{{  !empty($donvi->name)?$donvi->name:'' }}" >
                                </div>
                            </div>
                        </div>
                        </center>
                        <h6 style="padding-top:20px"><span>II. THÔNG TIN CÂU TRẢ LỜI</span></h6>
                        <div class="form-group col-md-12" data-form="add">
                            <div class="row" style="font-family: 'Roboto', sans-serif;">
                                @foreach($datas->question as $key=>$value)
                                <span style="padding-top:10px;font-weight: 600;">Câu {{$key+1}}: {{$value->name_convert}}</span>
                                    @foreach($value->listoption as $key=>$valueChild)
                                    <div style="padding-left:70px;padding-top:10px">
                                        @if($value->dap_an_dung == $valueChild['c_ques'])
                                            <span> <span style="color:#00ff4c;font-weight: 600;">{{$valueChild['c_ques']}}</span> : {{$valueChild['name']}}</span>
                                        @elseif($value->dap_an == $valueChild['c_ques'])
                                            <span> <span style="color:red;font-weight: 600;">{{$valueChild['c_ques']}}</span> : {{$valueChild['name']}}</span>
                                        @else
                                            <span> <span style="color:">{{$valueChild['c_ques']}}</span> : {{$valueChild['name']}}</span>
                                        @endif
                                    </div>
                                    @endforeach
                                @endforeach
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