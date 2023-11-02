
<form id="frmAddDocuments" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="PK_CMS_DOCUMENT" id="PK_CMS_DOCUMENT" value="<?= isset($arrSingle['PK_CMS_DOCUMENT']) ? $arrSingle['PK_CMS_DOCUMENT'] : '' ?>">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT VĂN BẢN</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Số ký hiệu</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_SYMBOL" name="C_SYMBOL"  value="<?= isset($arrSingle['C_SYMBOL']) ? $arrSingle['C_SYMBOL'] : '' ?>"/>
                </div>
                <div class="col-md-2"><label class="control-label required">Loại văn bản</label></div>
                <div class="col-md-4">
                    <select class="chzn-select form-control" name="C_DOCTYPE" id="C_DOCTYPE">
                        <option value="">--Chọn loại VB--</option>
                        <?php
                        foreach ($arrLoaiVanBan as $key => $value) {
                            if (isset($arrSingle->C_DOCTYPE) && $arrSingle->C_DOCTYPE == $value['C_CODE']) {
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
                <div class="col-md-2"><label class="control-label required">Ngày ban hành</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" onkeyup="DateOnkeyup(this, event)" type="text" id="C_DATE_PUBLIC" name="C_DATE_PUBLIC" value="<?= isset($arrSingle['C_DATE_PUBLIC']) ? $arrSingle['C_DATE_PUBLIC'] : '' ?>">
                </div>
                <div class="col-md-2"><label class="control-label">Ngày có hiệu lực</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" onkeyup="DateOnkeyup(this, event)" type="text" id="C_DATE_EFFECT" name="C_DATE_EFFECT" value="<?= isset($arrSingle['C_DATE_EFFECT']) ? $arrSingle['C_DATE_EFFECT'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Cơ quan ban hành</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_UNIT_PUBLIC" name="C_UNIT_PUBLIC"  value="<?= isset($arrSingle['C_UNIT_PUBLIC']) ? $arrSingle['C_UNIT_PUBLIC'] : '' ?>"/>
                </div>
                <div class="col-md-2"><label class="control-label">Người ký duyệt</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_SIGNER" name="C_SIGNER"  value="<?= isset($arrSingle['C_SIGNER']) ? $arrSingle['C_SIGNER'] : '' ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Trích yếu</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_SUBJECT" name="C_SUBJECT"  value="<?= isset($arrSingle['C_SUBJECT']) ? $arrSingle['C_SUBJECT'] : '' ?>"/>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col-md-2"><label class="control-label">Số thứ tự</label></div>
                <div class="col-md-2">
                    <input class="form-control" type="text" id="C_ORDER" name="C_ORDER" value="{{$C_ORDER}}" xml_data="false" column_name="C_ORDER"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Trạng thái</label></div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (!isset($arrSingle['C_STATUS']) || $arrSingle->C_STATUS == 'HOAT_DONG') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_STATUS" name="C_STATUS">Hoạt động</label>
                </div>
                <div class="col-md-8" style="margin-top: 5px; display: none" id="DECISION_PROCEDURE">
                    <label><input type="checkbox" id="C_IS_DECISION_PROCEDURE" name="C_IS_DECISION_PROCEDURE" {{ isset($C_IS_DECISION_PROCEDURE) && $C_IS_DECISION_PROCEDURE == 1 ? 'checked' : '' }}> Quyết định về thủ tục hành chính</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">File đính kèm</label></div>
                <div class="col-md-10">
                    <input type="file" class="form-control" id="images" style="width: 95px;" name="images[]" onchange="JS_Documents.preview_images();" multiple/>
                </div>
            </div>
            <div class="row" id="image_preview">
                <?php
                if (isset($arrSingle->C_FILE_NAME)) {
                    $filename = $arrSingle->C_FILE_NAME;
                    $arrFileName = explode(',', $filename);
                    if ($arrFileName[0] != '' & $arrFileName[0] != null) {
                        $i = 0;
                        foreach ($arrFileName as $value) {
                            $urlfile = url('public/cms_attach_file/' . $objLib->_getfolderbyfilename($value));
                            $arrValue = explode('!~!', $value);
                            ?>
                            <div data-id="<?= isset($arrSingle['PK_CMS_DOCUMENT']) ? $arrSingle['PK_CMS_DOCUMENT'] : '' ?>" id="file-preview-{{$i}}" class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='<?= $urlfile ?>'> <?= $arrValue[1] ?></a> <i class='fa fa-trash-o' onclick="JS_Documents.deletefileInSerVer('<?= $value ?>','<?= $i ?>')"></i></div>
                            <?php
                            $i++;
                        }
                    }
                }
                ?>

            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_Documents.updateAndNew($('form#frmAddDocuments'))" class="btn btn-primary " type="button">Cập nhật và thêm mới</button>
                    <button onclick="JS_Documents.update($('form#frmAddDocuments'))" class="btn btn-primary " type="button"><?= Lang::get('System::Common.submit') ?></button>
                    <button class="btn btn-danger " id="close_modal"><?= Lang::get('System::Common.close') ?></button>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
    function docTypeChange() {
        var qd = $('#C_DOCTYPE').val();
        if (qd == 'QD') {
            $('#DECISION_PROCEDURE').show();
        } else {
            $('#DECISION_PROCEDURE').hide();
        }
    }
    $(docTypeChange());
    $(document).on('change', '#C_DOCTYPE', docTypeChange);
</script>
<style type="text/css">
    #frmAddDocuments .row{
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
