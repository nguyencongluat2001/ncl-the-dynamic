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
</style>
<form id="frmAddAlbumImages" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="PK_CMS_ALBUM_IMAGES" id="PK_CMS_ALBUM_IMAGES" value="{{$PK_CMS_ALBUM_IMAGES}}">
    <input type="hidden" name="filename" id="filename" value="<?= isset($arrSingle['C_IMAGE_FILE_NAME']) ? $arrSingle['C_IMAGE_FILE_NAME'] : '' ?>">
    <input type="hidden" name="image_onserver" id="image_onserver" value="">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT ALBUM ẢNH</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Tên album</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_NAME" name="C_NAME" value="<?= isset($arrSingle['C_NAME']) ? $arrSingle['C_NAME'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Đường dẫn</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_URL" name="C_URL" value="<?= isset($arrSingle['C_URL']) ? $arrSingle['C_URL'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label ">Ngày bắt đầu</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" type="text" id="C_BEGIN_DATE" name="C_BEGIN_DATE" value="<?= isset($arrSingle['C_BEGIN_DATE']) ? $objLib->_yyyymmddToDDmmyyyy($arrSingle['C_BEGIN_DATE'], true) : '' ?>">
                </div>
                <div class="col-md-2"><label class="control-label ">Ngày kết thúc</label></div>
                <div class="col-md-4">
                    <input class="form-control input-md datepicker" type="text" id="C_END_DATE" name="C_END_DATE" value="<?= isset($arrSingle['C_END_DATE']) ? $objLib->_yyyymmddToDDmmyyyy($arrSingle['C_END_DATE'], true) : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Tùy chọn</label></div>
                <div class="col-md-2">
                    <label><input type="checkbox" <?= ($C_STATUS == '1') ? 'checked' : '' ?> id="C_STATUS" name="C_STATUS"> Hoạt động</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Số thứ tự</label></div>
                <div class="col-md-2">
                    <input class="form-control" type="text" id="C_ORDER" name="C_ORDER" value="{{$C_ORDER}}" xml_data="false" column_name="C_ORDER"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Ảnh đính kèm</label></div>
                <div class="col-md-5">
                    <input type="5" class="form-control" id="album-images" style="width: 88px;" name="album-images" onchange="JS_AlbumImages.preview_albumimages();"/>
                </div>
                <div class="col-md-5">
                    <input type="button" class="btn btn-primary" id="images_on_server" style="width: 200px;" value="ẢNH SERVER" name="images_on_server" onclick="JS_AlbumImages.preview_images_onserver()"/>
                </div>
            </div>
            <div class="row" id="image_preview">
                <div class="col-md-2"></div>
                <?php
                if (isset($arrSingle)) {
                    ?>
                    <div class='col-md-3'><img class='img-responsive' src='<?= $arrSingle['C_IMAGE_FILE_NAME'] ?>'></div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <button onclick="JS_AlbumImages.update($('form#frmAddAlbumImages'))" class="btn btn-primary " type="button">{{Lang::get('System::Common.submit')}}</button>
                    <button class="btn btn-danger " id="close_modal">{{Lang::get('System::Common.close')}}</button>
                </div>
            </div>
        </div>
    </section>
</form>
<script type="text/javascript">
    $('#C_NAME').change(function () {
        var date = new Date();
        var value = $(this).val();
        value = convertTitleToUrl(value);
        $('#C_URL').val(value + '-' + date.getFullYear() + (date.getMonth() + 1) + date.getDate() + '.html');
    });
</script>