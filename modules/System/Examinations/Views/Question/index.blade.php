@extends('System.layouts.index')
@section('pageName', 'QUẢN TRỊ CÂU HỎI')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        EfyLib.loadFileJsCss(arrJsCss);

        var baseUrl = '{{ url('') }}';
        var JS_Questions = new JS_Questions(baseUrl, 'system/examinations', 'questions');
        jQuery(document).ready(function($) {
            JS_Questions.loadIndex();
        })
    </script>

<!-- <script>
    $(".datepicker").datepicker({
        format: 'yyyy-MM-dd'
    });
</script> -->
@endsection
@section('content')
    
    <form id="frmQuestion_index">
        @csrf
        
        <!-- <input type="hidden" id="_filexml" value="danh_sach_don_vi_trien_khai.xml"> -->
        <input type="hidden" name="dot_thi_id" id="dot_thi_id" value="{{isset($dot_thi_id) ? $dot_thi_id : ''}}">

        

        <section class="content-wrapper">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row form-group input-group-index">
                        <div class="col-md-1 form-group"></div>
                        <div class="col-md-4 form-group">
                            <div class="row ">
                                <label class="col-md-3 control-label" style="padding-top:2%">Trạng thái</label>
                                <div class="col-md-7">
                                    <select class="form-control input-sm chzn-select" name="trang_thai" id="trang_thai">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                        </div>
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

                    </div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-primary btn-sm shadow-sm" id="btn_add" type="button">
                                <i class="fas fa-plus fa-sm"></i> Thêm
                            </button>
                            <button class="btn btn-success btn-sm shadow-sm" id="btn_edit" type="button">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-danger btn-sm shadow-sm" id="btn_delete" type="button">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                            <button class="btn btn-info btn-sm shadow-sm" onclick="JS_Questions.back(this.value)" type="button">
                                <i class="fas fa-arrow-alt-circle-left"></i> Quay lại
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
