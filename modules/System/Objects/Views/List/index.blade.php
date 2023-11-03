@extends('System.layouts.index')

@section('pageName', 'QUẢN TRỊ ĐỐI TƯỢNG')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib .loadFileJsCss(arrJsCss);

        var baseUrl = '{{ url('') }}';
        var JS_Objects = new JS_Objects(baseUrl, 'system', 'objects');
        jQuery(document).ready(function($) {
            JS_Objects.loadIndex();
        })
    </script>

<script>
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy'
    });
</script>
@endsection

@section('content')
    <form id="frmlist_index">
        @csrf
        <!-- <input type="hidden" id="_filexml" value="danh_sach_don_vi_trien_khai.xml"> -->
        <section class="content-wrapper">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row form-group input-group-index">
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Từ ngày</label>
                                <div class="col-md-7">
                                      <input class="form-control input-md datepicker" type="text" id="ngay_bat_dau" name="ngay_bat_dau"
                                      value="{{ !empty($ngay_bat_dau)?date('d/m/Y', strtotime($ngay_bat_dau)):'' }}">
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Đến ngày</label>
                                <div class="col-md-7">
                                      <input class="form-control input-md datepicker" type="text" id="ngay_ket_thuc" name="ngay_ket_thuc"
                                      value="{{ !empty($ngay_ket_thuc)?date('d/m/Y', strtotime($ngay_ket_thuc)):'' }}">
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Cấp đơn vị</label>
                                <div class="col-md-7">
                                <select onchange="JS_Objects.getUnit(this.value)" class="form-select form-select-md" id="cap_don_vi"name="cap_don_vi" >
                                    <option value="">Tất cả</option>
                                    <option value="SO_NGANH">Sở - ngành</option>
                                    <option value="QUAN_HUYEN">Quận - huyện</option>
                                    <option value="PHUONG_XA">Phường - xã</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Đơn vị</label>
                                <div class="col-md-7" id="unit">
                                    <select class="form-select form-select-md" id="don_vi" name="don_vi">
                                        <option value="">Chưa chọn cấp đơn vị</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <center>
                        <div class="col-md-6">
                            <div class="input-group input-group-md">
                                <input type="text" id="search_text" name="search" class="form-control"
                                    placeholder="Nhập từ khóa tìm kiếm">
                                <button type="button" class="input-group-text d-block search" id="btn_search"
                                    style="right: 0; z-index: 5;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        </center>
                        

                    </div>
                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-5 text-end">
                            <!-- <button class="btn btn-primary btn-sm shadow-sm" id="btn_add" type="button">
                                <i class="fas fa-plus fa-sm"></i> Thêm
                            </button>
                            <button class="btn btn-success btn-sm shadow-sm" id="btn_edit" type="button">
                                <i class="fas fa-edit"></i> Sửa
                            </button> -->
                            <button class="btn btn-warning btn-sm shadow-sm" id="btn_export" type="button">
                                <i class="fas fa-file-download"></i> Xuất danh sách
                            </button>
                        </div>
                    </div>
                    <!-- Màn hình danh sách -->
                    <div class="row" id="table-container"></div>
                    <!-- Phân trang dữ liệu -->
                    <!-- <div class="row" id="pagination"></div> -->
                </div>
            </div>
        </section>
        
    </form>
    <!-- Hien thi modal -->
    <div class="modal fade" id="editModal" role="dialog"></div>

    <div class="modal" id="addModal" role="dialog"></div>
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
@endsection
