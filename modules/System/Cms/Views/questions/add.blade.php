<form id="frmAddQuestions" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="PK_QUESTIONS" id="PK_QUESTIONS" value="<?= isset($arrSingle['PK_CMS_QUESTION']) ? $arrSingle['PK_CMS_QUESTION'] : '' ?>">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT CÂU HỎI</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Lĩnh vực</label></div>
                <div class="col-md-10">
                    <select id="C_CATEGORY" name="C_CATEGORY" class="form-control input-sm chzn-select">
                        <option value="">--Chọn lĩnh vực--</option>
                        <?php
                        foreach ($arrCategory as $key => $value) {
                            if (isset($arrSingle->C_CATEGORY) && $arrSingle->C_CATEGORY == $value['C_CODE']) {
                                echo '<option selected value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            } else {
                                echo '<option value="' . $value['C_CODE'] . '">' . $value['C_NAME'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nội dung câu hỏi</label></div>
                <div class="col-md-10">
                    <textarea class="form-control input-md" type="text" id="C_CONTENT" name="C_CONTENT"  xml_data="false" column_name="C_CONTENT"><?= isset($arrSingle['C_CONTENT']) ? $arrSingle['C_CONTENT'] : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Loại câu hỏi</label></div>
                <?php
                foreach ($arrQuestionType as $key => $value ) {
                    if (isset($arrSingle->C_QUESTION_TYPE) && $arrSingle->C_QUESTION_TYPE == $value['C_CODE']) {
                        $check = 'checked';
                    } else {
                        $check = '';
                    }
                    if (!isset($arrSingle->C_QUESTION_TYPE)) {
                        $check = 'checked';
                    }
                ?>
                <div class="col-md-2">
                    <label><input type="radio"  <?= $check ?> value="<?= $value['C_CODE'] ?>" name="C_QUESTION_TYPE"> <?= $value['C_NAME'] ?></label>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Trạng thái câu hỏi</label></div>
                <?php
                foreach ($arrStatusQuestion as $key => $value) {
                if (isset($arrSingle->C_STATUS_QUESTION) && $arrSingle->C_STATUS_QUESTION == $value['C_CODE']) {
                    $check = 'checked';
                } else {
                    $check = '';
                }
                if (!isset($arrSingle->C_STATUS_QUESTION)) {
                    $check = 'checked';
                }
                ?>
                <div class="col-md-2">
                    <label><input type="radio" <?= $check ?> value="<?= $value['C_CODE'] ?>" name="C_STATUS_QUESTION"> <?= $value['C_NAME'] ?></label>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tùy chọn</label></div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (!isset($arrSingle['C_STATUS']) || $arrSingle->C_STATUS == 'HOAT_DONG') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_STATUS" name="C_STATUS">Hoạt động</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_Questions.update($('form#frmAddQuestions'))" class="btn btn-primary " type="button"><?= Lang::get('System::Common.submit') ?></button>
                    <button class="btn btn-danger " id="close_modal"><?= Lang::get('System::Common.close') ?></button>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
    $('.chzn-select').chosen({
        height: '100%',
        width: '100%'
    });
</script>
<style type="text/css">
    #frmAddQuestions .row{
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
