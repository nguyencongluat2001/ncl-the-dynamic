@extends('System.layouts.index')
@section('content')
<!-- /.content --> 
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    EFYLib.loadFileJsCss(arrJsCss);</script>
<form action="" method="GET" id="frmSchedulerIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/scheduler") }}', 'list');"><i class="fa fa-list"></i>Quản trị video</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row form-group input-group-index">
                    <div class="col-md-2" style="text-align: right"><label class="control-label">Tuần</label></div>
                    <div class="col-md-4">
                        <select class="form-control chzn-select" onchange="$('form#frmSchedulerIndex').submit()" id="C_WEEK" name="C_WEEK">
                            <?= $arryWekk ?>
                        </select>
                    </div>
                    <div class="col-md-2"><label class="control-label">Năm</label></div>
                    <div class="col-md-2">
                        <select class="form-control chzn-select" onchange="$('form#frmSchedulerIndex').submit()" id="C_YEAR" name="C_YEAR">
                            <?php
                            for ($i = -1; $i < 6; $i++) {
                                if ((date('Y') + $i) == $iYear) {
                                    echo "<option selected value='" . ( date('Y') + $i) . "'> " . ( date('Y') + $i ) . "</option>";
                                } else {
                                    echo "<option value='" . ( date('Y') + $i) . "'> " . ( date('Y') + $i ) . "</option>";
                                }
                            }
                            ?>
                        </select>
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
                            <col width="3%">
                            <col width="5%">
                            <col width="5%">
                            <col width="42%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                                </td>
                                <td colspan="2" class="title" align="center">
                                    Thời gian</td>
                                <td align="center">
                                    Nội dung công việc</td>
                                <td align="center">Thành phần tham dự</td>
                                <td  align="center">
                                    Phòng ban chuẩn bị</td>
                                <td align="center">Địa điểm/Lái xe</td>
                            </tr>
                            <tr/>
                        </thead>
                        <tbody id="data-list-scheduler">
                            <?php
                            foreach ($arrySchedule_Unit as $key => $value) {
                                $size = sizeof($value);
                                $i = 0;
                                foreach ($value as $k => $v) {
                                    if ($i == 0) {
                                        ?>
                                        <tr>
                                            <td onclick="{select_row(this); }">
                                                <input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this); }" name="chk_item_id" value="<?= $v['PK_SCHEDULE_UNIT'] ?>">
                                            </td>
                                            <td rowspan="<?= $size ?>" onclick="{select_row(this); }">
                                                <?= $v['C_DAY_NAME'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_DAY_PART'] ?>
                                            </td >
                                            <td onclick="{select_row(this); }">
                                                <span style="color: red"> <?= $v['C_START_TIME'] ?>-<?= $v['C_FINISH_TIME'] ?></span>:  <?= $v['C_WORK_NAME'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_ATTENDING'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_PREPARE_ORGAN'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_PLACE'] ?>
                                            </td>

                                        </tr>

                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td onclick="{select_row(this); }">
                                                <input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this); }" name="chk_item_id" value="<?= $v['PK_SCHEDULE_UNIT'] ?>">
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_DAY_PART'] ?>
                                            </td >
                                            <td onclick="{select_row(this); }">
                                                <span style="color: red"> <?= $v['C_START_TIME'] ?>-<?= $v['C_FINISH_TIME'] ?></span>:  <?= $v['C_WORK_NAME'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_ATTENDING'] ?>

                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_PREPARE_ORGAN'] ?>
                                            </td>
                                            <td onclick="{select_row(this); }">
                                                <?= $v['C_PLACE'] ?>
                                            </td>

                                        </tr>

                                        <?php
                                    }
                                    $i++;
                                }
                            }
                            ?>
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
<div class="modal-content" id="modalImages">

</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var JS_Scheduler = new JS_Scheduler(baseUrl, 'system/cms', 'scheduler');
    jQuery(document).ready(function($) {
        JS_Scheduler.loadIndex();
    $('.chzn-select').chosen();
    })
</script>
<style>
    #table-mo-ta tr td{
        padding: 3px;
    }
    #table-mo-ta {
        width: 100%;
    }
</style>
@endsection

