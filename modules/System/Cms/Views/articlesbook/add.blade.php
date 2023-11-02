
<form id="frmAddArticles" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="PK_ARTICLES" id="PK_ARTICLES" value="<?= isset($arrSingle['PK_CMS_ARTICLE_BOOK']) ? $arrSingle['PK_CMS_ARTICLE_BOOK'] : '' ?>">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT BÀI VIẾT</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Ấn phẩm</label></div>
                <div class="col-md-10">
                    <button type="button" class="btn btn-primary" onclick="JS_ArticlesBook.search_document()">Chọn ấn phẩm</button>
                    <input type="hidden" value="<?= isset($arrSingle['FK_PUBLICATION']) ? $arrSingle['FK_PUBLICATION'] : '' ?>" name="FK_PUBLICATION" id="FK_PUBLICATION" />
                    <label id="text_nhande"><?= isset($arrSingle['C_FULL_NAME']) ? $arrSingle['C_FULL_NAME'] : '' ?></label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Ngày đăng</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" onkeyup="DateOnkeyup(this, event)" type="text" id="C_CREATE_DATE" name="C_CREATE_DATE" value="<?= isset($arrSingle['C_CREATE_DATE']) ? $arrSingle['C_CREATE_DATE'] : date('d/m/Y') ?>">
                </div>
                <div class="col-md-2"><label class="control-label required">Tác giả</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md" type="text" id="C_AUTHOR" name="C_AUTHOR" value="<?= isset($arrSingle['C_AUTHOR']) ? $arrSingle['C_AUTHOR'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nguồn tin</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_SOURCE" name="C_SOURCE"  value="<?= isset($arrSingle['C_SOURCE']) ? $arrSingle['C_SOURCE'] : '' ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Tiêu đề</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_TITLE" name="C_TITLE"  value="<?= isset($arrSingle['C_TITLE']) ? $arrSingle['C_TITLE'] : '' ?>"/>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col-md-2"><label class="control-label required">Đường dẫn</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_SLUG" name="C_SLUG" value="<?= isset($arrSingle['C_SLUG']) ? $arrSingle['C_SLUG'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Ảnh bài viết</label></div>
                <div class="col-md-2">
                    <input class="form-control input-md" style="padding-left: 5px; width: 80px;"  type="file" id="C_FEATURE_IMG" onchange="JS_ArticlesBook.PreviewFeatureImage()" name="C_FEATURE_IMG" value="">
                </div>
                <div class="col-md-8" id="PreviewFeatureImage">
                    <div class='col-md-3'><?php
                        if (isset($arrSingle['C_FEATURE_IMG_BASE']) && $arrSingle['C_FEATURE_IMG_BASE'] != null && $arrSingle['C_FEATURE_IMG_BASE'] != '') {
                            ?>
                            <img class='img-responsive' src="<?= $arrSingle['C_FEATURE_IMG'] ?>">
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Trích dẫn</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_SUBJECT" name="C_SUBJECT" value="<?= isset($arrSingle['C_SUBJECT']) ? $arrSingle['C_SUBJECT'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Nội dung bài viết</label></div>
                <div class="col-md-10">
                    <textarea class="form-control input-md" type="text" id="C_CONTENT" name="C_CONTENT"  xml_data="false" column_name="C_CONTENT"><?= isset($arrSingle['C_CONTENT']) ? $arrSingle['C_CONTENT'] : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tiêu đề SEO</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_TITLE_SEO" name="C_TITLE_SEO" value="<?= isset($arrSingle['C_TITLE_SEO']) ? $arrSingle['C_TITLE_SEO'] : '' ?>" xml_data="false" column_name="C_TITLE_SEO">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Miêu tả SEO</label></div>
                <div class="col-md-10">
                    <textarea class="form-control"  id="C_DESCRIPTION_SEO" name="C_DESCRIPTION_SEO"><?= isset($arrSingle['C_DESCRIPTION_SEO']) ? $arrSingle['C_DESCRIPTION_SEO'] : '' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tùy chọn</label></div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (!isset($arrSingle['C_DESCRIPTION_SEO']) || $arrSingle['C_STATUS'] == 'HOAT_DONG') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_STATUS" name="C_STATUS">Hoạt động</label>
                </div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (isset($arrSingle['C_IS_COMMENT']) && $arrSingle['C_IS_COMMENT'] == '1') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox"  <?= $check ?>  id="C_IS_COMMENT" name="C_IS_COMMENT">Hiển thị bình luận</label>
                </div>
                <div class="col-md-2">
                    <?php
                    $check = '';
                    if (isset($arrSingle['C_IS_HIDE_RELATE_ATICLES']) && $arrSingle['C_IS_HIDE_RELATE_ATICLES'] == '1') {
                        $check = 'checked';
                    }
                    ?>
                    <label><input type="checkbox" <?= $check ?> id="C_IS_HIDE_RELATE_ATICLES" name="C_IS_HIDE_RELATE_ATICLES">Ẩn tin liên quan</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Loại tin bài</label></div>
                <?php
                foreach ($arrLoaiTinBai as $key => $value) {
                    if (isset($arrSingle['C_ARTICLES_TYPE']) && $arrSingle['C_ARTICLES_TYPE'] == $value['C_CODE']) {
                        $check = 'checked';
                    } else {
                        $check = '';
                    }
                    if (!isset($arrSingle['C_ARTICLES_TYPE']) && $value['C_CODE'] == 'TIN_HIEN_THI_CO_DINH') {
                        $check = 'checked';
                    }
                    ?>
                    <div class="col-md-2">
                        <label><input type="radio"  <?= $check ?> value="<?= $value['C_CODE'] ?>" name="C_ARTICLES_TYPE"> <?= $value['C_NAME'] ?></label>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Trạng thái tin bài</label></div>
                <?php
                foreach ($arrTrangThaiTinBai as $key => $value) {
                    if (isset($arrSingle['C_STATUS_ARTICLES_BOOK']) && $arrSingle['C_STATUS_ARTICLES_BOOK'] == $value['C_CODE']) {
                        $check = 'checked';
                    } else {
                        $check = '';
                    }
                    if (!isset($arrSingle['C_STATUS_ARTICLES_BOOK']) && $value['C_CODE'] == 'TIN_HIEN_THI_CO_DINH') {
                        $check = 'checked';
                    }
                    ?>
                    <div class="col-md-2">
                        <label><input type="radio" <?= $check ?> value="<?= $value['C_CODE'] ?>" name="C_STATUS_ARTICLES"> <?= $value['C_NAME'] ?></label>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Thứ tự hiển thị</label></div>
                <div class="col-md-1">
                    <input class="form-control input-md" type="text" id="C_ORDER" name="C_ORDER" value="<?= isset($arrSingle['C_ORDER']) ? $arrSingle['C_ORDER'] : '' ?>" xml_data="false" column_name="C_ORDER">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">File đính kèm</label></div>
                <div class="col-md-10">
                    <input type="file" class="form-control" id="images" style="padding-left: 5px; width: 80px;" name="images[]" onchange="JS_ArticlesBook.preview_images();" multiple/>
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
                            <div data-id="<?= isset($arrSingle['PK_CMS_ARTICLE']) ? $arrSingle['PK_CMS_ARTICLE'] : '' ?>" id="file-preview-{{$i}}" class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='<?= $urlfile ?>'> <?= $arrValue[1] ?></a> <i class='fa fa-trash-o' onclick="JS_Articles.deletefileInSerVer('<?= $value ?>', '<?= $i ?>')"></i></div>
                            <?php
                            $i++;
                        }
                    }
                }
                ?>

            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_ArticlesBook.update($('form#frmAddArticles'))" class="btn btn-primary " type="button"><?= Lang::get('System::Common.submit') ?></button>
                    <button class="btn btn-danger " id="close_modal"><?= Lang::get('System::Common.close') ?></button>
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
    var states = <?php echo json_encode($arrAuthorReturn); ?>;
    $('#C_AUTHOR').autocomplete({
        source: [states]
    });
    var filebrowserBrowseUrl = '<?= $filebrowserBrowseUrl ?>';
    var filebrowserUploadUrl = '<?= $filebrowserUploadUrl ?>';
    var filebrowserImageBrowseUrl = '<?= $filebrowserImageBrowseUrl ?>';
    CKEDITOR.replace('C_CONTENT', {
        filebrowserBrowseUrl: filebrowserBrowseUrl,
        filebrowserUploadUrl: filebrowserUploadUrl,
        filebrowserImageBrowseUrl: filebrowserImageBrowseUrl,
        filebrowserUploadMethod: 'form',
    });
    $('input[name="FK_CATEGORIES"]').change(function () {
        $('#C_TITLE').change();
    });
    $('#C_TITLE').change(function () {
        var date = new Date();
        var value = $(this).val();
        value = convertTitleToUrl(value);
        $('#C_SLUG').val(value + '-' + date.getTime() + '.html');
    });
    $("#TreeCategories").jstree({
        "core": {"expand_selected_onload": false, multiple: false},
        "plugins": ["themes", "html_data", "checkbox", "search"],
        "search": {
            "case_sensitive": false,
            "show_only_matches": true
        }
    });

</script>
<style type="text/css">
    #frmAddArticles .row{
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
