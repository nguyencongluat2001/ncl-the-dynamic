@extends('System.layouts.index')

@section('breadcrumb1', 'Quản trị danh mục')
@section('breadcrumb2', 'Danh mục đối tượng')
@section('pageName', 'DANH MỤC ĐỐI TƯỢNG')

@section('script')
    <script type="text/javascript">
        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib .loadFileJsCss(arrJsCss);

        var baseUrl = '{{ url('') }}';
        var Js_List = new Js_List(baseUrl, 'system/listtype', 'list');
        jQuery(document).ready(function($) {
            Js_List.loadIndex();
        })
    </script>
@endsection

@section('content')
    <form id="frmlist_index">
        @csrf
        <input type="hidden" id="_filexml" value="danh_sach_don_vi_trien_khai.xml">

        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5 text-end">
                <button class="btn btn-primary btn-sm shadow-sm" id="btn_add" type="button">
                    <i class="fas fa-plus fa-sm"></i> Thêm
                </button>
                <button class="btn btn-success btn-sm shadow-sm" id="btn_edit" type="button">
                    <i class="fas fa-edit"></i> Sửa
                </button>
                <button class="btn btn-danger btn-sm shadow-sm" id="btn_delete" type="button">
                    <i class="fas fa-trash-alt"></i> Xóa
                </button>
            </div>
        </div>

        <section class="content-wrapper">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row form-group input-group-index">
                        <div class="col-md-6 form-group">
                            <div class="row ">
                                <label class="col-md-3 control-label">Chọn danh mục</label>
                                <div class="col-md-7">
                                    <select class="form-control input-sm chzn-select" name="listtype" id="listtype">
                                        @foreach ($arrListTypes as $arrListType)
                                            @if ($arrListType['name'])
                                                <option value="{{ $arrListType['id'] }}">{{ $arrListType['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <input type="text" id="search_text" name="search" class="form-control"
                                    placeholder="Nhập từ khóa tìm kiếm">
                                <button type="button" class="input-group-text d-block search" id="btn_search"
                                    style="right: 0; z-index: 5;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
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
    <div class="modal fade" id="addListModal" role="dialog"></div>

@endsection
