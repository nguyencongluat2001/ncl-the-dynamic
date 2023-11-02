<style type="text/css">
    #frmAddAlbumImages .row{
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
    .row{
        margin-top: 5px;
    }
</style>
<form id="frmSeeComment" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="PK_ARTICLES_COMMENT" id="PK_ARTICLES_COMMENT" value="{{$arrSingle['PK_ARTICLES_COMMENT']}}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">Thông tin phản hồi</label>
        </ol>
        <div class="container ">
            <div class="row">
            <div class="col-md-1"></div>
                <div class="col-md-2"><label class="control-label required">Người gửi</label></div>
                <div class="col-md-8">
                    <input class="form-control input-md" type="text" id="C_NAME_SENDER" name="C_NAME_SENDER" value="<?= isset($arrSingle['C_NAME_SENDER']) ? $arrSingle['C_NAME_SENDER'] : '' ?>">
                </div>
                <!-- <div class="col-md-2"><label class="control-label required">Điện thoại</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_PHONE_SENDER" name="C_PHONE_SENDER" value="<?= isset($arrSingle['C_PHONE_SENDER']) ? $arrSingle['C_PHONE_SENDER'] : '' ?>">
                </div> -->
            </div>
            <!-- <div class="row">
                <div class="col-md-2"><label class="control-label required">Địa chỉ</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_ADDRESS_SENDER" name="C_ADDRESS_SENDER" value="<?= isset($arrSingle['C_ADDRESS_SENDER']) ? $arrSingle['C_ADDRESS_SENDER'] : '' ?>">
                </div>
            </div> -->
            <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2"><label class="control-label required">Ngày gửi</label></div>
                <div class="col-md-8">
                    <input class="form-control input-md" type="text" id="C_SEND_DATE" name="C_SEND_DATE" value="<?= isset($arrSingle['C_SEND_DATE']) ? date('d/m/Y',strtotime($arrSingle['C_SEND_DATE'])) : '' ?>">
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-2"><label class="control-label required">Email</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_EMAIL_SENDER" name="C_EMAIL_SENDER" value="<?= isset($arrSingle['C_EMAIL_SENDER']) ? $arrSingle['C_EMAIL_SENDER'] : '' ?>">
                </div>
                <div class="col-md-2"><label class="control-label required">Ngày gửi</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_SEND_DATE" name="C_SEND_DATE" value="<?= isset($arrSingle['C_SEND_DATE']) ? $arrSingle['C_SEND_DATE'] : '' ?>">
                </div>
            </div> -->
            <!-- <div class="row">
                <div class="col-md-2"><label class="control-label required">Tiêu đề</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_SUBJECT" name="C_SUBJECT" value="<?= isset($arrSingle['C_SUBJECT']) ? $arrSingle['C_SUBJECT'] : '' ?>">
                </div>
            </div> -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2"><label class="control-label required">Nội dung</label></div>
                <div class="col-md-8">
                    <textarea class="form-control" rows="3"  id="C_CONTENT" name="C_CONTENT" ><?= isset($arrSingle['C_CONTENT']) ? $arrSingle['C_CONTENT'] : '' ?></textarea>
                    <!-- <input class="form-control input-md" type="text" id="C_CONTENT" name="C_CONTENT" value="<?= isset($arrSingle['C_CONTENT']) ? $arrSingle['C_CONTENT'] : '' ?>"> -->
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-2"><label class="control-label">Số thứ tự</label></div>
                <div class="col-md-2">
                    <input class="form-control" type="text" id="C_ORDER" name="C_ORDER" value="{{$arrSingle['C_ORDER']}}" xml_data="false" column_name="C_ORDER"/>
                </div>
            </div> -->
            <!-- <div class="row">
                <div class="col-md-2"><label class="control-label">File đính kèm</label></div>
                <div class="col-md-10">
                    <div class="row" id="image_preview">
                        <?php
                        $filename = $arrSingle['C_FILE_NAME'];
                        $arrFileName = explode(',', $filename);
                        $objLib = new Modules\Core\EFY\Library();
                        if ($arrFileName[0] != '' & $arrFileName[0] != null) {
                            foreach ($arrFileName as $value) {
                                $urlfile = url('public/cms_attach_file/' . $objLib->_getfolderbyfilename($value));
                                $arrValue = explode('!~!', $value);
                                ?>
                                <div  class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='<?= $urlfile ?>'> <?= $arrValue[1] ?></a> <i class='fa fa-trash-o' onclick="JS_Articles.deletefileInSerVer('<?= $value ?>')"></i></div>
                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_Articles.ApproveComment($('form#frmSeeComment'))" class="btn btn-primary " type="button">Duyệt comment</button>
                    <button class="btn btn-danger " id="close_modal_2">{{Lang::get('System::Common.close')}}</button>
                </div>
            </div>
        </div>
    </section>
</form>