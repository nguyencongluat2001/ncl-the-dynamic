@extends('system.layouts_Backend.index')
@section('body')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_BangCap.js') }}"></script>
    {{-- <link  href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" /> --}}
    <div class="container-fluid">
        <div class="row">
            <form action="" method="POST" id="frmProduct_index">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <div class="breadcrumb-input-fix d-sm-flex align-items-center">
                    <span>
                        <a href="{{ URL::asset('/system/product/index') }}">
                            <button class="btn btn-light btn-sm shadow-sm" id=""
                                type="button"data-toggle="tooltip" data-original-title="Thêm danh mục"><i
                                    class="fas fa-book-medical"></i> Giấy Khám</button>
                        </a>
                    </span>
                    <span>
                        <a href="{{ URL::asset('/system/product/indexBangCap') }}">
                            &nbsp;
                            <button class="btn btn-success btn-sm shadow-sm" id=""
                                type="button"data-toggle="tooltip" data-original-title="Thêm danh mục"><i
                                    class="fas fa-list-alt"></i> Bằng cấp</button>
                        </a>
                    </span>

                </div>
                <section class="content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <div class="breadcrumb-input-right">
                                        <button class="btn btn-danger shadow-sm" id="btn_delete"
                                            type="button"data-toggle="tooltip" data-original-title="Xóa thể loại"><i
                                                class="fas fa-trash-alt"></i></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control input-sm chzn-select" name="cate" id="cate">
                                        <option value=''>---Chọn Bằng Cấp---</option>
                                        @foreach ($getCategory as $item)
                                            <option value="{{ $item['code_cate'] }}">{{ $item['name_category'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group" style="width:40%;height:10%">
                                    <!-- <span class="input-group-text text-body"><i class="fas fa-search"
                                                                                                        aria-hidden="true"></i></span> -->
                                    <input id="search" name="search" type="text" class="form-control"
                                        placeholder="Từ khóa tìm kiếm...">
                                </div>
                                <button style="width:8%" id="txt_search" name="txt_search" type="button"
                                    class="btn btn-dark"><i class="fas fa-search"></i></button>
                            </div>
                            <!-- Màn hình danh sách -->
                            <div class="row" id="table-container-category" style="padding-top:10px"></div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editmodal" role="dialog"></div>
    <div class="modal " id="addfile" role="dialog"></div>

    <div id="dialogconfirm"></div>
    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = "{{ url('') }}";
        var JS_BangCap = new JS_BangCap(baseUrl, 'system', 'product');
        jQuery(document).ready(function($) {
            JS_BangCap.loadIndex(baseUrl);
        })
    </script>
@endsection
