@extends('System.layouts.index')
@section('content')

@section('breadcrumb1', 'Quản trị Tin bài')
@section('pageName', 'Danh sách tin bài')
<!-- /.content -->
<style>
    .row {
        margin-bottom: 5px;
    }
</style>
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    EfyLib.loadFileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmArticlesIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Từ ngày:</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fromdate" id="fromdate" class="form-control datepicker">
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Đến ngày:</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="todate" id="todate" class="form-control datepicker">
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Chuyên mục:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="category" name="category" class="form-control input-sm chzn-select">
                            <option value="">--Chọn chuyên mục --</option>
                            @if(isset($arrCategory))
                            @foreach($arrCategory as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Loại tin bài:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="articles_type" name="articles_type" class="form-control input-sm chzn-select">
                            <option value="">--Loại tin bài--</option>
                            @if(isset($arrLoaiTinBai))
                            @foreach($arrLoaiTinBai as $key => $value)
                            <option value="{{ $value['code'] }}">{{ $value['name'] }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Trạng thái:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="status" name="status" class="form-control input-sm chzn-select">
                            <option value="">--Chọn trạng thái --</option>
                            @if(isset($arrTrangThaiTinBai))
                            @foreach($arrTrangThaiTinBai as $key => $value)
                            <option value="{{ $value['code'] }}">{{ $value['name'] }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <input name="search" class="form-control" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary search mb-0" id="btn_search" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-group input-group-index">
                    <div class="pull-right" align="right">
                        {{--<button class="btn btn-basic " id="btn_manager_comment" type="button" data-toggle="tooltip" data-original-title="Quản trị phản hồi"><i class="fa fa-cogs"></i> Quản trị phản hồi</button>--}}
                        {{--<button class="btn btn-info" id="btn_approval" type="button"><i class="fa fa-edit"></i> Duyệt tin</button>--}}
                        <button class="btn btn-warning" id="btn_see" type="button"><i class="fa fa-eye"></i> Xem</button>
                        <button class="btn btn-primary" id="btn_add" type="button" data-toggle="tooltip" data-original-title="{{Lang::get('System::Common.add')}}"><i class="fas fa-plus"></i></button>
                        <button class="btn btn-success" id="btn_edit" type="button" data-toggle="tooltip" data-original-title="{{Lang::get('System::Common.edit')}}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fas fa-trash" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container"></div>
            </div>
        </div>
    </section>
</form>
<!-- Hien thi modal -->
<div class="modal-content" id="modalArticles"></div>
<div class="modal fade" id="addList" role="dialog"></div>

<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var JS_Articles = new JS_Articles(baseUrl, 'system/cms', 'articles');
    jQuery(document).ready(function($) {
        JS_Articles.loadIndex();
    });
    $('.datepicker').datepicker({
        dateFormat: 'dd/MM/yy',
    });
    $('.chzn-select').chosen({height: '100%',width: '100%'});
</script>
@endsection