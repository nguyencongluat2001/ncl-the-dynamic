@extends('System.layouts.index')

@section('breadcrumb1', 'Quản trị danh mục')
@section('breadcrumb2', 'Loại danh mục')
@section('pageName', 'LOẠI DANH MỤC')

@section('script')
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    EfyLib.loadFileJsCss(arrJsCss);

    var baseUrl = "{{ url('') }}";
    var JS_Listtype = new Js_Listtype(baseUrl, 'system/listtype', 'listtype');
    jQuery(document).ready(function($) {
        JS_Listtype.loadIndex();
    })
</script>
@endsection

@section('content')
    <form id="frmlisttype_index">
        @csrf
        <input type="hidden" id="_filexml" name="_filexml" value="loai_danh_muc.xml">

    <div class="breadcrumb-input-fix d-sm-flex align-items-center justify-content-between">
        <div class="breadcrumb-input-right">
            <!-- <button class="btn btn-primary btn-sm shadow-sm" id="btn_pushtounit" type="button" data-toggle="tooltip" data-original-title="Xuất danh mục về các đơn vị"><i class="fas fa-sign-in"></i>Xuất danh mục</button> -->
            <button class="btn btn-primary btn-sm shadow-sm" id="btn_add" type="button">
                <i class="fas fa-plus"></i> Thêm
            </button>
            <button class="btn btn-success btn-sm shadow-sm" id="btn_edit" type="button">
                <i class="fas fa-edit"></i> Sửa
            </button>
            <button class="btn btn-danger btn-sm shadow-sm" id="btn_delete" type="button">
                <i class="fas fa-trash-alt"></i> Xóa
            </button>
            <!-- <button class="btn btn-default btn-sm shadow-sm" id="btn_export_cache" type="button"><i class="fas fa-download"></i> Xuất cache</button> -->
        </div>
    </div>

    <section class="content-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row form-group input-group-index">
                    <div class="col-md-6 form-group">
                        <div class="row ">
                            <div class="input-group input-group-sm">
                                <input type="text" id="search_text" name="search" class="form-control" placeholder="Nhập từ khóa tìm kiếm">
                                <button type="button" class="input-group-text d-block search" id="btn_search" style="right: 0; z-index: 5;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container"></div>
                <!-- Phân trang dữ liệu -->
                <div class="row" id="pagination"></div>
            </div>
        </div>
    </section>
</form>

<!-- Hien thi modal -->
<div class="modal fade" id="addListypeModal" role="dialog"></div>

@endsection