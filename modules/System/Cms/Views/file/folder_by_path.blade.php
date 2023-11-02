<?php
$arrInputPath = explode('\\', $input_path);
$x = array_pop($arrInputPath);
$backpath = implode('\\', $arrInputPath);
if ($input_path != base_path('public\ckeditor_file')) {
    ?>
    <div class="col-md-4">
        <img ondblclick='<?= $objJS ?>.selectFolder("<?= base64_encode($backpath) ?>")' src="<?= url('') ?>/filemanager/img/ico/folder.png"/><br>
        <a><?= 'Back' ?></a>
    </div>
    <?php
}
?>

<?php
foreach ($arrDir as $value) {
    if ($value == '.' || $value == '..') {
        continue;
    }
    if (is_dir($input_path . '/' . $value)) {
        ?>
        <div class="col-md-4">
            <img ondblclick="<?= $objJS ?>.selectFolder('<?= base64_encode($input_path . '/' . $value) ?>')" src="<?= url('') ?>/filemanager/img/ico/folder.png"/><br>
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