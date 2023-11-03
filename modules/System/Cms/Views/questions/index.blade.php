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
<form action="index" method="POST" id="frmQuestionsIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb">
            <li><a role="button" onclick="LoadModul('{{ url("system/images") }}', 'list');"><i
                        class="fa fa-list"></i> Quản trị câu hỏi</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row" style="margin-bottom: 5px">
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Loại câu hỏi:</label>
                    </div>
                    <div class="col-md-3">
                        <select id="questions_type" name="questions_type" class="form-control input-sm chzn-select" >
                            <option value="">--Chọn loại câu hỏi--</option>
                            <?php
                            foreach ($arrQuestionType as $key => $value) {
                                echo '<option value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Lĩnh vực:</label>
                    </div>
                    <div class="col-md-3">
                        <select id="category" name="category" class="form-control input-sm chzn-select" >
                            <option value="">--Chọn lĩnh vực--</option>
                            <?php
                            foreach ($arrCategory as $key => $value) {
                                echo '<option value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Trạng thái:</label>
                    </div>
                    <div class="col-md-3">
                        <select id="status_question" name="status_question" class="form-control input-sm chzn-select" >
                            <option value="">--Trạng thái--</option>
                            <?php
                            foreach ($arrStatusQuestion as $key => $value) {
                                echo '<option value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            }
                            ?>
                        </select>
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
                                <button class="btn btn-primary  search" id="btn_search"
                                        data-loading-text="{{Lang::get('System::Common.search')}}..."
                                        type="button">{{Lang::get('System::Common.search')}}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-group input-group-index">
                    <div class="pull-right">
                        <button class="btn btn-basic " id="btn_answer" type="button" data-toggle="tooltip"
                                data-original-title="Trả lời"><i class="fa fa-reply"></i> Trả lời</button>
                        <button class="btn btn-primary " id="btn_add" type="button" data-toggle="tooltip"
                                data-original-title="{{Lang::get('System::Common.add')}}"><i class="fa fa-plus"></i>
                        </button>
                        <button class="btn btn-success " id="btn_edit" type="button" data-toggle="tooltip"
                                data-original-title="{{Lang::get('System::Common.edit')}}"><i
                                class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation"
                                data-btn-ok-label="{{Lang::get('System::Common.delete')}}"
                                data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger"
                                data-btn-cancel-label="{{Lang::get('System::Common.close')}}"
                                data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default"
                                data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i
                                class="fa fa-trash-o"></i></button>
                    </div>
                </div>
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center"
                           id="table-data">
                        <colgroup>
                            <col width='5%'/>
                            <col width='55%'/>
                            <col width='15%'/>
                            <col width='15%'/>
                            <col width='10%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                        <tr class="header">
                            <td align="center">
                                <b><input type="checkbox" name="chk_all_item_id"
                                          onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                            </td>
                            <td align="center">
                                <b>Nội dung</b>
                            </td>
                            <td align="center">
                                <b>Lĩnh vực</b>
                            </td>
                            <td align="center">
                                <b>Trạng thái trả lời</b>
                            </td>
                           
                            <td align="center">
                                <b>Tình trạng</b>
                            </td>
                        <tr/>
                        </thead>
                        <tbody id="data-list-questions">

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
<div class="modal-content" id="modalQuestions">
</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var JS_Questions = new JS_Questions(baseUrl, 'system/cms', 'questions');
    jQuery(document).ready(function($) {
        JS_Questions.loadIndex();
    });
    $('.chzn-select').chosen();
</script>
@endsection
