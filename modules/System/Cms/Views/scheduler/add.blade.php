<?php
//dd($arrSingle);
?>
<style type="text/css">
    #frmAddImages .row{
        margin-top: 10px;
    }
    h1 {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        color: #b53310;
        text-transform: uppercase;
        margin-top: 0px;
        margin-bottom: 0px;
    }
    section{
        margin-bottom: 20px;
    }
</style>
<form id="frmAddImages" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="PK_SCHEDULE_UNIT" id="PK_SCHEDULE_UNIT" value="{{$PK_SCHEDULE_UNIT}}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT VIDEO</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Tuần</label></div>
                <div class="col-md-4">
                    <select class="form-control chzn-select" id="C_WEEK" name="C_WEEK">
                        <?= $arryWekk ?>
                    </select>
                </div>
                <div class="col-md-2"><label class="control-label required">Năm</label></div>
                <div class="col-md-4">
                    <select class="form-control chzn-select" id="C_YEAR" name="C_YEAR">
                        <?php
                        for ($i = -1; $i < 6; $i++) {
                            if ($i == 0) {
                                echo "<option selected value='" . ( date('Y') + $i) . "'> " . ( date('Y') + $i ) . "</option>";
                            } else {
                                echo "<option value='" . ( date('Y') + $i) . "'> " . ( date('Y') + $i ) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Ngày trong tuần</label></div>
                <div class="col-md-2">
                    <select class="form-control chzn-select" id="C_DAY" name="C_DAY">
                        <?php
                        foreach ($arrDayInWeek as $key => $value) {
                            echo "<option value='" . $value['C_CODE'] . "'> " . $value['C_NAME'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-1"><label class="control-label required">Buổi</label></div>
                <div class="col-md-2">
                    <select class="form-control chzn-select" id="C_DAY_PART" name="C_DAY_PART">
                        <option value="BUOI_SANG">Buổi sáng</option>
                        <option value="BUOI_CHIEU">Buổi chiều</option>
                    </select>
                </div>
                <div class="col-md-1"><label class="control-label">Bắt đầu </label></div>
                <div class="col-md-1">
                    <input class="form-control input-md" maxlength="5" type="text" id="C_START_TIME" name="C_START_TIME" value="<?= isset($arrSingle['C_START_TIME']) ? $arrSingle['C_START_TIME'] : '' ?>">
                </div>
                <div class="col-md-1"><label class="control-label">Kết thúc</label></div>
                <div class="col-md-1">
                    <input class="form-control input-md" maxlength="5" type="text" id="C_FINISH_TIME" name="C_FINISH_TIME" value="<?= isset($arrSingle['C_FINISH_TIME']) ? $arrSingle['C_FINISH_TIME'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nội dung công việc</label></div>
                <div class="col-md-10">
                    <textarea id="C_WORK_NAME" name="C_WORK_NAME" class="form-control" placeholder=""><?= isset($arrSingle['C_WORK_NAME']) ? $arrSingle['C_WORK_NAME'] : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Chủ trì</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_NAME_JOINER" name="C_NAME_JOINER" value="<?= isset($arrSingle['C_NAME_JOINER']) ? $arrSingle['C_NAME_JOINER'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Địa điểm/Lái xe</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_PLACE" name="C_PLACE" value="<?= isset($arrSingle['C_PLACE']) ? $arrSingle['C_PLACE'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">CQ Chuẩn bị nội dung</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_PREPARE_ORGAN" name="C_PREPARE_ORGAN" value="<?= isset($arrSingle['C_PREPARE_ORGAN']) ? $arrSingle['C_PREPARE_ORGAN'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Thành phần tham dự</label></div>
                <div class="col-md-10">
                    <textarea id="C_ATTENDING" name="C_ATTENDING" class="form-control" placeholder=""><?= isset($arrSingle['C_ATTENDING']) ? $arrSingle['C_ATTENDING'] : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tình trạng</label></div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (!isset($arrSingle['C_STATUS']) || $arrSingle['C_STATUS'] == 1) {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_STATUS" name="C_STATUS">Hoạt động</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="text-align: center">
                <button onclick="JS_Scheduler.update($('form#frmAddImages'))" class="btn btn-primary " type="button">{{Lang::get('System::Common.submit')}}</button>
                <button class="btn btn-danger " id="close_modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>

    </section>
</form>
<script type="text/javascript">
    if ($('input[name="C_SOURCE_VIDEO"]:checked').val() == 'LINK') {
        $('.uploadvideos').hide();
        $('.linkvideos').show();
    } else {
        $('.uploadvideos').show();
        $('.linkvideos').hide();
    }
    $('#C_NAME').change(function () {
        var date = new Date();
        var value = $(this).val();
        value = convertTitleToUrl(value);
        $('#C_URL').val(value + '-' + date.getFullYear() + (date.getMonth() + 1) + date.getDate() + '.html');
    });
    $('input[name="C_SOURCE_VIDEO"]').change(function () {
        if (this.value == 'LINK') {
            $('.uploadvideos').hide();
            $('.linkvideos').show();
        } else {
            $('.uploadvideos').show();
            $('.linkvideos').hide();
        }
    });
</script>