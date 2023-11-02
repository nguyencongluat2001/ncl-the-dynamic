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
    EFYLib.loadFileJsCss(arrJsCss);</script>
<form action="index" method="POST" id="frmDocumentsIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/documents") }}', 'list');"><i class="fa fa-list"></i> Quản trị bài viết</a></li>
        </ol>
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
                        <label class="control-label">Loại văn bản:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="documents_type" name="documents_type" class="form-control input-sm chzn-select" >
                            <option value="">--Loại văn bản--</option>
                            <?php
                            foreach ($arrLoaiVanBan as $key => $value) {
                                echo '<option value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Ngày có hiệu lực</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="dateeffect" id="dateeffect" class="form-control datepicker">
                    </div>
                </div>
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
                        <button class="btn btn-primary " id="btn_add" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.add')}}"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-success " id="btn_edit" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.edit')}}"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col width='5%'/>
                            <col width='50%'/>
                            <col width='10%'/>
                            <col width='10%'/>
                            <col width='15%'/>
                            <col width='10%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                                </td>
                                <td align="center">
                                    <b>Trích yếu</b>
                                </td>
                                <td align="center">
                                    <b>Số ký hiệu</b>
                                </td>
                                <td align="center">
                                    <b>Loại văn bản</b>
                                </td>
                                <td align="center">
                                    <b>Người ký</b>
                                </td>
                                <td align="center">
                                    <b>Tình trạng</b>
                                </td>
                            </tr>
                            <tr/>
                        </thead>
                        <tbody id="data-list-documents">

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
<div class="modal-content" id="modalDocuments">
</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var JS_Documents = new JS_Documents(baseUrl, 'system/cms', 'documents');
    jQuery(document).ready(function($) {
    JS_Documents.loadIndex();
    });
    $('.datepicker').datepicker();
    $('.chzn-select').chosen();
</script>
@endsection

