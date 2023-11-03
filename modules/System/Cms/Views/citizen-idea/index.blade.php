@extends('System.layouts.index')
@section('content')
<!-- /.content --> 
<style>
    .row{
        margin-bottom: 5px;
    }
</style>
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    NclLib .loadFileJsCss(arrJsCss);</script>
<form action="index" method="POST" id="frmCitizenIdeaIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/citizen-idea") }}', 'list');"><i class="fa fa-list"></i> Quản trị góp ý</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Tìm kiếm chung:</label>
                    </div>
                    <div class="col-md-11">
                        <div class="input-group input-group-sm">
                            <input name="search" class="form-control" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary  search" id="btn_search" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-group input-group-index">
                    <div class="pull-right">
                        <button class="btn btn-success " id="btn_edit" type="button" data-toggle="tooltip"  data-original-title="Xem/Sửa"><i class="fa fa-eye"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col width='5%'/>
                            <col width='15%'/>
                            <col width='15%'/>
                            <col width='15%'/>
                            <col width='15%'/>
                            <col width='25%'/>
                            <col width='10%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                                </td>
                                <td>
                                    <b>Tên người gửi</b>
                                </td>
                                <td>
                                    <b>Địa chỉ người gửi</b>
                                </td>
                                <td>
                                    <b>Số điện thoại</b>
                                </td>
                                <td>
                                    <b>Email</b>
                                </td>
                                <td>
                                    <b>Tiêu đề</b>
                                </td>
                                <td align="center">
                                    <b>Trạng thái</b>
                                </td>
                            </tr>
                            <tr/>
                        </thead>
                        <tbody id="data-list-citizen-idea">

                        </tbody>
                    </table>
                </div>
                <!-- Phân trang dữ liệu -->
                <div class="row" id="pagination"></div>
            </div>
        </div>
    </section>
</form>
<!-- Hien thi modal -->
<div class="modal-content" id="modalCitizenIdea">
</div>
<div class="modal fade"  role="dialog" id="modalDialog">
</div>

<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var JS_CitizenIdea = new JS_CitizenIdea(baseUrl, 'system/cms', 'citizen-idea');
    jQuery(document).ready(function($) {
        JS_CitizenIdea.loadIndex();
    });
    $('.chzn-select').chosen();
</script>
@endsection

