<form id="frmAddRecord" role="form" action="" method="POST">
    <div class="modal-dialog modal-hg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Duyệt file trên máy chủ</h4>
            </div>
            <div class="modal-body modal-body-record form-row">
                <div class="panel panel-info">
                    <div class="panel-heading">Thư mục</div>
                    <div class="panel-body">
                        <!-- Nội dung tài liệu khác -->
                        <div class="row" id="DuLieuFolder">
                            <?php
                            foreach ($arrDir as $value) {
                                if ($value == '.' || $value == '..') {
                                    continue;
                                }
                                if (is_dir($input_path . '\\' . $value)) {
                                    ?>
                                    <div class="col-md-4">
                                        <img ondblclick='<?= $objJS ?>.selectFolder("<?= base64_encode($input_path . '\\' . $value) ?>")' src="<?= url('') ?>/filemanager/img/ico/folder.png"/><br>
                                        <a><?= $value ?></a>
                                    </div>
                                    <?php
                                }
                                if (is_file($input_path . '/' . $value) && (strtolower(pathinfo($value, PATHINFO_EXTENSION)) == 'jpg' || strtolower(pathinfo($value, PATHINFO_EXTENSION)) == 'png')) {
                                    ?>
                                    <div class="col-md-4">
                                        <img ondblclick="<?= $objJS ?>.selectImage('<?= $urlPath . '/' . $value ?>')" style="width: 122px;height: 91px" src="<?= $urlPath . '/' . $value ?>"/><br>
                                        <a><?= $value ?></a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary" type="button">Chọn</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>