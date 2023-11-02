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
    <input type="hidden" name="PK_CMS_VIDEOS" id="PK_CMS_VIDEOS" value="{{$PK_CMS_VIDEOS}}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <label style="margin: 0;color: #b53310">CẬP NHẬT VIDEO</label>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Vị trí hiển thị</label></div>
                <div class="col-md-4">
                    <input type="radio" name="C_SOURCE_VIDEO" id="LINK" <?php
                    if (isset($arrSingle['C_SOURCE_VIDEO']) && $arrSingle['C_SOURCE_VIDEO'] == 'LINK' || !isset($arrSingle['C_SOURCE_VIDEO'])) {
                        echo 'checked';
                    }
                    ?> value="LINK"> <label class="control-Link" for="LINK">Link</label>
                    <input type="radio" name="C_SOURCE_VIDEO" id="UPLOAD" value="UPLOAD" <?php
                    if (isset($arrSingle['C_SOURCE_VIDEO']) && $arrSingle['C_SOURCE_VIDEO'] == 'UPLOAD') {
                        echo 'checked';
                    }
                    ?> style="margin-left: 10px"> <label class="control-Link" for="UPLOAD">Tải lên</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Tiêu đề video</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_NAME" name="C_NAME" value="<?= isset($arrSingle['C_NAME']) ? $arrSingle['C_NAME'] : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label required">Đường dẫn bài viết</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_URL" name="C_URL" value="<?= isset($arrSingle['C_URL']) ? $arrSingle['C_URL'] : '' ?>">
                </div>
            </div>
            <div class="row linkvideos">
                <div class="col-md-2"><label class="control-label required">Địa chỉ video</label></div>
                <div class="col-md-10">
                    <input class="form-control input-md" type="text" id="C_URL_VIDEO" name="C_URL_VIDEO" value="<?= isset($arrSingle['C_URL_VIDEO']) ? $arrSingle['C_URL_VIDEO'] : '' ?>">
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
                    <label><input type="checkbox"  <?= ($C_STATUS=='1')?'checked':''?>  id="C_STATUS" name="C_STATUS"> Hoạt động</label>
                </div>
                <div class="col-md-2"><label class="control-label">Số thứ tự</label></div>
                <div class="col-md-2">
                    <input class="form-control" type="text" id="C_ORDER" name="C_ORDER" value="{{$C_ORDER}}" xml_data="false" column_name="C_ORDER"/>
                </div>
            </div>
            <div class="row uploadvideos" >
                <div class="col-md-2"><label class="control-label">Tải video đính kèm</label></div>
                <div class="col-md-10">
                    <input type="file" class="form-control" id="videos" style="width: 95px;" name="videos" onchange="JS_Videos.preview_videos();"/>
                </div>
            </div>
            <div class="row uploadvideos" id="video_preview">
                <?php
                if (isset($arrSingle)) {
                    $arrFile = explode('!~!', $arrSingle['C_VIDEO_FILE_NAME']);
                    ?>
                    <div class="col-md-2"></div>
                    <div class='col-md-3'>
                        <div id='file-video'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='<?= $arrSingle['C_VIDEO_FILE_NAME'] ?>'><?= isset($arrFile[1]) ? $arrFile[1] : '' ?> </a><i class='fa fa-trash-o' onclick="JS_Videos.deletefile('<?= $arrSingle['C_VIDEO_FILE_NAME'] ?>')"></i></div>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
        <?php
        if (isset($arrSingle)) {
            if ($arrSingle['C_SOURCE_VIDEO'] == 'UPLOAD') {
                ?>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class='col-md-9'>
                        <video controls style="width: 100%">
                            <source src='<?= $arrSingle['C_VIDEO_FILE_NAME'] ?>' type="video/mp4">
                        </video>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class='col-md-9'>
                        <iframe width="100%" height="409" src="<?= $arrSingle['C_VIDEO_FILE_NAME'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>

                <?php
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12" style="text-align: center">
                <button onclick="JS_Videos.update($('form#frmAddImages'))" class="btn btn-primary " type="button">{{Lang::get('System::Common.submit')}}</button>
                <button class="btn btn-danger " id="close_modal">{{Lang::get('System::Common.close')}}</button>
            </div>
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