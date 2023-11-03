@extends('System.layouts.index')

@section('breadcrumb1', 'Quản trị Người dùng')
@section('pageName', 'NGƯỜI DÙNG & ĐƠN VỊ')

@section('script')
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    NclLib .loadFileJsCss(arrJsCss);

    var baseUrl = "{{ url('') }}";
    var Js_User = new Js_User(baseUrl, 'system', 'users');
    jQuery(document).ready(function($) {
        Js_User.loadIndex();
    })
</script>

<script>
    $(".datepicker").datepicker({
        format: 'yyyy-MM-dd'
    });
</script>
@endsection

@section('content')
<form id="frmUser_index">
    @csrf
    <input type="hidden" id="_token" value='{{ csrf_token() }}'>
    <!-- Page Heading -->
    <div class="breadcrumb-input-fix d-sm-flex align-items-center justify-content-between">
        <div class="breadcrumb-input-right">
            <button id="btn-add" type="button" class="btn btn-primary btn-sm shadow-sm"><i class="fas fa-plus"></i> Thêm</button>
            <button id="btn-edit" type="button" class="btn btn-success btn-sm shadow-sm"><i class="fas fa-edit"></i> Sửa</button>
            <button id="btn-delete" type="button" class="btn btn-danger btn-sm shadow-sm"><i class="fas fa-trash"></i> Xóa</button>
        </div>
    </div>
    <section class="content-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row form-group input-group-index">
                    <div class="col-md-6 form-group">
                        <div class="row ">
                            <div class="input-group input-group-sm">
                                <input type="text" id="search_text" name="search" class="form-control" placeholder="Nhập từ khóa tìm kiếm" onkeydown="if(event.key == 'Enter'){Js_User.search(); return false;}">
                                <button type="button" class="input-group-text d-block search" id="search-user-unit" style="right: 0; z-index: 5;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div id="dataList"></div>
                </div>
            </div>
        </div>


    </section>
</form>

<div class="modal fade" id="addmodal" role="dialog"></div>

@endsection