@php
    use Modules\Core\Ncl\FunctionHelper;
@endphp
<form id="frmAdd" role="form" action="" method="POST">
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{isset($datas->id) ? $datas->id : ''}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content  card">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật đợt thi</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    {{-- Tên đợt --}}
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Tên đợt</label>
                                <div class="col-md-8">
                                    <input class="form-control input-md" type="text" id="ten" name="ten"
                                        value="{{ $datas->ten ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <!-- Hội thi năm-->
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Hội thi năm</label>
                                <div class="col-md-8">
                                    <select class="form-control input-md chzn-select" name="nam"
                                        id="nam">
                                        <!-- <option value=''>-- Chọn năm --</option>
                                        <option value='2023'>2023</option>
                                        <option value='2024'>2024</option> -->
                                        @foreach ($datas['arrListYear'] as $arrListYear)
                                            @if ($arrListYear['name'])
                                                <option value="{{ $arrListYear['code'] }}" {{isset($datas->nam) && $datas->nam == $arrListYear['code'] ? 'selected' : ''}}>{{ $arrListYear['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Ngày bắt đầu</label>
                                <div class="col-md-8">
                                    <input class="form-control input-md datepicker" type="text" id="ngay_bat_dau" name="ngay_bat_dau" 
                                        value="{{ !empty($datas->ngay_bat_dau)?date('d/m/Y', strtotime($datas->ngay_bat_dau)):'' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Ngày kết thúc</label>
                                <div class="col-md-8">
                                    <input class="form-control input-md datepicker" type="text" id="ngay_ket_thuc" name="ngay_ket_thuc" 
                                        value="{{ !empty($datas->ngay_ket_thuc)?date('d/m/Y', strtotime($datas->ngay_ket_thuc)):'' }}">
                                </div>
                            </div>
                        </div>
                        {{--  Thời gian làm bài --}}
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Thời gian thi</label>
                                <div class="col-md-5">
                                    <input class="form-control input-md" type="text" id="thoi_gian_lam_bai" name="thoi_gian_lam_bai"
                                        value="{{ $datas->thoi_gian_lam_bai ?? '' }}">
                                </div>
                                <label class="radio ms-0 col-md-3 text-right control-label" style="padding-top:2%">
                                    Giây
                                </label>
                            </div>
                        </div>
                        {{-- trạng thái --}}
                        <div class="form-group col-md-6" data-form="add">
                            <div class="row">
                                <label align="right" class="radio ms-0 col-md-4 text-right control-label required" style="padding-top:2%">Trạng thái</label>
                                <div class="col-md-8"style="padding-top:2%">
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
                            <span id="btupdate">
                                <button id='btn_create' class="btn btn-primary btn-sm" type="button">
                                    Cập nhật
                                </button>
                            </span>
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                                Đóng
                            </button>
                        </div>
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
<script>
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy'
    });
</script>