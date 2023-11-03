@extends('System.layouts.index')

@section('pageName', 'QUẢN TRỊ BÀI THI')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib .loadFileJsCss(arrJsCss);

        var baseUrl = '{{ url('') }}';
        var JS_Exams = new JS_Exams(baseUrl, 'system', 'exams');
        jQuery(document).ready(function($) {
            JS_Exams.loadIndex();
            JS_Exams.getDotThi();
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
                                <label style="padding-top:1%" class="col-md-2 control-label">Cấp đơn vị</label>
                                <div class="col-md-7">
                                <select onchange="JS_Exams.getUnit(this.value)" class="form-select form-select-md" id="cap_don_vi"name="cap_don_vi" >
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
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Năm thi</label>
                                <div onchange="JS_Exams.getDotThi(this.value)" class="col-md-7">
                                    <select  class="form-control input-sm chzn-select" name="nam" id="nam">
                                        @foreach ($arrListYear as $arrListYear)
                                            @if ($arrListYear['name'])
                                                <option value="{{ $arrListYear['code'] }}">{{ $arrListYear['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label style="padding-top:1%" class="col-md-2 control-label">Đợt thi</label>
                                <div class="col-md-7" id="dot">
                                    <select class="form-select form-select-md" id="dot_thi" name="dot_thi">
                                        <option value="">Chưa chọn năm</option>
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
                            <button class="btn btn-warning btn-sm shadow-md" id="btn_export" type="button">
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

@endsection
