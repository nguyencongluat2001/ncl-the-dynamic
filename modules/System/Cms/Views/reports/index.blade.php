@extends('System.layouts.index')
@section('content')
<!-- /.content -->
<style>
.row {
    margin-bottom: 5px;
}
</style>
<script type="text/javascript">
var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
NclLib .loadFileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmReportsIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb">
            <li><a role="button" onclick="LoadModul('{{ url("system/reports") }}', 'list');"><i class="fa fa-list"></i>
                    Quản trị báo cáo</a></li>
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
                        <label class="control-label">Chuyên mục:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="category" name="category" class="form-control input-sm chzn-select">
                            <option value="">--Chọn chuyên mục --</option>
                            <?php
                                foreach ($arrCategory as $key => $value) {
                                    echo '<option value="' . $value->PK_CATEGORIES . '">' . $value->C_NAME . '</option>';
                                }
                                ?>
                        </select>
                    </div>

                    <div class="col-md-1" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Loại báo cáo:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="report_type" name="report_type" class="form-control input-sm chzn-select"
                            onchange="location = this.value;">
                            <option value="{{ url('system/cms/reports') }}">Báo cáo tổng quan</option>
                            <option value="{{ url('system/cms/reports/detail') }}">Báo cáo chi tiết theo từng đơn vị
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-1"></div>
                    <div class="col-md-8">
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
                        <button class="btn btn-info" id="btn_export_excel" type="button"><i class="fa fa-file-excel-o"
                                aria-hidden="true"></i>&ensp;Xuất Excel</button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center"
                        id="table-data">
                        <colgroup>
                            <col width='5%' />
                            <col width='15%' />
                            <col width='40%' />
                            <col width='15%' />
                            <col width='10%' />
                            <col width='15%' />
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b>STT</b>
                                </td>
                                <td align="center">
                                    <b>Ngày đăng tin</b>
                                </td>
                                <td align="center">
                                    <b>Tiêu đề</b>
                                </td>
                                <td align="center">
                                    <b>Chuyên mục</b>
                                </td>
                                <td align="center">
                                    <b>Tác giả</b>
                                </td>
                                <td align="center">
                                    <b>Cán bộ đăng tin</b>
                                </td>
                            </tr>
                            <tr />
                        </thead>
                        <tbody id="data-list-reports">

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
</div>
<script type="text/javascript">
$('.export').hide();
$('.category').hide();
var baseUrl = '{{ url("") }}';
var JS_Reports = new JS_Reports(baseUrl, 'system/cms', 'reports');
jQuery(document).ready(function($) {
    JS_Reports.loadIndex();
});
$('.datepicker').datepicker();
$('.chzn-select').chosen();
</script>
@endsection