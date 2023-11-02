<form id="frmEditCitizenIdea" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="PK_CMS_CITIZEN_IDEA" id="PK_CMS_CITIZEN_IDEA" value="{{ $arrSingle['PK_CMS_CITIZEN_IDEA'] }}">
    <input type="hidden" name="FILE_NAME_HIDDEN" id="FILE_NAME_HIDDEN" value="{{ $arrSingle['C_FILE_NAME'] }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT GÓP Ý</label>
        </ol>
        <div class="container" style="margin-top: 10px;">
            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>Người gửi:</b> </label></div>
                <label for="" class="col-md-4">{{ $arrSingle['C_NAME_SENDER'] }}</label>
                <div class="col-md-2"><label class="control-label"><b>Địa chỉ người gửi:</b> </label></div>
                <label for="" class="col-md-4">{{ $arrSingle['C_ADDRESS_SENDER'] }}</label>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>Số điện thoại người gửi:</b></label></div>
                <label for="" class="col-md-4">{{ $arrSingle['C_PHONE_SENDER'] }}</label>

                <div class="col-md-2"><label class="control-label"><b>Email người gửi:</b></label></div>
                <label for="" class="col-md-4">{{ $arrSingle['C_EMAIL_SENDER'] }}</label>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>Tiều đề:</b></label></div>
                <label for="" class="col-md-10">{{ $arrSingle['C_SUBJECT'] }}</label>
            </div>

            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>Nội dung:</b></label></div>
                <div class="col-md-10">
                    <p>{{ $arrSingle['C_CONTENT'] }}</p>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>Trạng thái:</b></label></div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (!isset($arrSingle['C_STATUS']) || $arrSingle->C_STATUS == 'DA_XEM') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_STATUS" name="C_STATUS"> Đã xem</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label"><b>File đính kèm:</b></label></div>
            </div>
            <div class="row" id="image_preview">
                <?php
                if (isset($arrSingle->C_FILE_NAME)) {
                    $filename = $arrSingle->C_FILE_NAME;
                    $arrFileName = explode(',', $filename);
                    if ($arrFileName[0] != '' & $arrFileName[0] != null) {
                        foreach ($arrFileName as $value) {
                            $urlfile = url('public/cms_attach_file/' . $objLib->_getfolderbyfilename($value));
                            $arrValue = explode('!~!', $value);
                            ?>
                            <div  class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='<?= $urlfile ?>'> <?= $arrValue[1] ?></a> <i class='fa fa-trash-o' onclick="JS_Articles.deletefileInSerVer('<?= $value ?>')"></i></div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_CitizenIdea.update($('form#frmEditCitizenIdea'))" class="btn btn-primary " type="button"><?= Lang::get('System::Common.submit') ?></button>
                    <button class="btn btn-danger " id="close_modal"><?= Lang::get('System::Common.close') ?></button>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">

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