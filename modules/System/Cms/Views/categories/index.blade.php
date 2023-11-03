@extends('System.layouts.index')

@section('breadcrumb1', 'Quản trị Chuyên mục')
@section('pageName', 'Danh sách chuyên mục')

@section('script')
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    NclLib .loadFileJsCss(arrJsCss);

    var baseUrl = "{{ url('') }}";
    var Js_Categories = new Js_Categories(baseUrl, 'system/cms', 'categories');
    jQuery(document).ready(function($) {
        Js_Categories.loadIndex();
    })
</script>
@endsection

@section('content')
<style>
    #tb_list_record th{
        padding: .5rem;
    }
</style>
<form id="frmCategories_index">
    @csrf
    <input type="hidden" id="_token" value='{{ csrf_token() }}'>
    <input type="hidden" id="check_add" value="categories" id-categories='{{ $id_root }}'>
    <!-- Page Heading -->
    <div class="breadcrumb-input-fix d-sm-flex align-items-center justify-content-between">
        <div class="breadcrumb-input-right">
            <button id="btn-add" type="button" class="btn btn-primary btn-sm shadow-sm"><i class="fas fa-plus"></i> Thêm</button>
        </div>
    </div>
    <section class="content-wrapper">
        <div class="row">
            <div class="col-md-4" style="width: 28%;" id="jstree-tree"></div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row form-group input-group-index">
                            <div class="col-md-6 form-group">
                                <div class="row ">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="search_text" name="search" class="form-control" placeholder="Nhập từ khóa tìm kiếm">
                                        <button type="button" class="input-group-text d-block search" id="search" style="right: 0; z-index: 5;">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="zend_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

<div class="modal fade" id="addmodal" role="dialog"></div>
<div class="modal fade" id="addList" role="dialog"></div>

@endsection