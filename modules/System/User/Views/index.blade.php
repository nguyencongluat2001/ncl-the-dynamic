@extends('system.layouts_Backend.index')
@section('body')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_User.js') }}"></script>

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    {{-- <link  href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" /> --}}
    <div class="container-fluid" >
        <div class="row">
            <form action="" method="POST" id="frmUsers_index">
                <input style="display:none" type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <div class="breadcrumb-input-fix d-sm-flex align-items-center justify-content-between" style="padding-top:20px">
                        <!-- <span><H5>DANH SÁCH TÀI KHOẢN</H5></span> -->
                    <!-- <div class="breadcrumb-input-right">
                        <button class="btn btn-success btn-sm shadow-sm" id="btn_add" type="button"data-toggle="tooltip"
                            data-original-title="Thêm người dùng"><i class="fas fa-user-plus"></i></button>
                        <button class="btn btn-warning btn-sm shadow-sm" id="btn_edit" type="button"data-toggle="tooltip"
                            data-original-title="SỬa người dùng"><i class="fas fa-user-edit"></i></button>
                        <button class="btn btn-danger btn-sm shadow-sm" id="btn_delete" type="button"data-toggle="tooltip"
                            data-original-title="Xóa người dùng"><i class="fas fa-user-times"></i></button>
                    </div> -->
                </div>
                <section class="content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row form-group">
                            <div class="col-md-3">
                            <button class="btn btn-success shadow-sm" id="btn_add" type="button"data-toggle="tooltip"
                            data-original-title="Thêm người dùng"><i class="fas fa-user-plus"></i></button>
                            <button class="btn btn-warning shadow-sm" id="btn_edit" type="button"data-toggle="tooltip"
                                data-original-title="SỬa người dùng"><i class="fas fa-user-edit"></i></button>
                            <button class="btn btn-danger shadow-sm" id="btn_delete" type="button"data-toggle="tooltip"
                            data-original-title="Xóa người dùng"><i class="fas fa-user-times"></i></button>
                            </div>
                            
                                <div class="col-md-2">
                                    <select class="form-control input-sm chzn-select" name="role"
                                        id="role">
                                        <option value=''>-- Quyền hiển thị --</option>
                                        <option value="ADMIN">Quản trị hệ thống - CEO</option>
                                    </select>
                                </div>
                                <div class="input-group" style="width:40%;height:10%">
                                    <!-- <span class="input-group-text text-body"><i class="fas fa-search"
                                            aria-hidden="true"></i></span> -->
                                    <input id="search" name="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                                </div>
                                <button style="width:5%" id="txt_search" name="txt_search" type="button" class="btn btn-dark"><i class="fas fa-search"></i></button>

                            </div>
                            <!-- Màn hình danh sách -->
                            <div class="row" id="table-container" style="padding-top:10px"></div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editmodal" role="dialog"></div>
    <div class="modal " id="editPassmodal" role="dialog" style=" width: 100%;height: 100%;background: #0000007d; background-repeat:no-repeat;background-size: cover;"></div>
    <div class="modal " id="addfile" role="dialog"></div>

    <div id="dialogconfirm"></div>
    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = '{{ url('') }}';
        var JS_User = new JS_User(baseUrl, 'system', 'user');
        $(document).ready(function($) {
            JS_User.loadIndex(baseUrl);
        })
    </script>
@endsection

