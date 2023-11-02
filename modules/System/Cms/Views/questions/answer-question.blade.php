<form action="index" method="POST" id="frmAnswerQuestion">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="FK_QUESTION" id="FK_QUESTION" value="{{ $FK_QUESTION }}">
    <input type="hidden" name="PK_ANSWERS" id="PK_ANSWERS" value="<?= isset($arrSingle['PK_CMS_ANSWER']) ? $arrSingle['PK_CMS_ANSWER'] : '' ?>">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">TRẢ LỜI</label>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"><label class="control-label required">Lĩnh vực</label></div>
                        <?php
                        foreach ($arrCategory as $key => $value ) {
                        if (isset($arrSingle->C_CATEGORY) && $arrSingle->C_CATEGORY == $value['C_CODE']) {
                            $check = 'checked';
                        } else {
                            $check = '';
                        }
                        if (!isset($arrSingle->C_CATEGORY)) {
                            $check = 'checked';
                        }
                        ?>
                        <div class="col-md-2">
                            <label><input type="radio"  <?= $check ?> value="<?= $value['C_CODE'] ?>" name="C_CATEGORY"> <?= $value['C_NAME'] ?></label>
                        </div>
                        <?php
                        }
                        ?>
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
                        <div class="col-md-2"><label class="control-label required">Trả lời</label></div>
                        <div class="col-md-10">
                            <textarea class="form-control input-md" type="text" id="C_ANSWER_CONTENT" name="C_ANSWER_CONTENT"  xml_data="false" column_name="C_ANSWER_CONTENT"><?= isset($arrSingle['C_CONTENT']) ? $arrSingle['C_CONTENT'] : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label">File đính kèm</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="file" style="width: 95px;" name="file[]" onchange="JS_Questions.preview_files();" multiple/>
                        </div>
                    </div>
                    <div class="row" id="image_preview">
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <button onclick="JS_Questions.send_answer($('form#frmAnswerQuestion'))" class="btn btn-primary " type="button"><?= Lang::get('System::Common.submit') ?></button>
                            <button class="btn btn-danger " id="close_modal"><?= Lang::get('System::Common.close') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
<?php
    $filebrowserBrowseUrl = url('filemanager/dialog.php?type=2&editor=ckeditor&fldr=');
    $filebrowserUploadUrl = url('filemanager/dialog.php?type=2&editor=ckeditor&fldr=');
    $filebrowserImageBrowseUrl = url('filemanager/dialog.php?type=1&editor=ckeditor&fldr=');
?>
var filebrowserBrowseUrl = '<?= $filebrowserBrowseUrl ?>';
var filebrowserUploadUrl = '<?= $filebrowserUploadUrl ?>';
var filebrowserImageBrowseUrl = '<?= $filebrowserImageBrowseUrl ?>';
CKEDITOR.replace('C_ANSWER_CONTENT', {
    filebrowserBrowseUrl: filebrowserBrowseUrl,
    filebrowserUploadUrl: filebrowserUploadUrl,
    filebrowserImageBrowseUrl: filebrowserImageBrowseUrl,
    filebrowserUploadMethod: 'form'
});
</script>
