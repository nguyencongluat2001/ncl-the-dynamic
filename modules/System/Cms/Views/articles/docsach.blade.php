<form action="index" method="POST" id="frmDocumentPaper_index" style="width: 80%;margin: auto">
    <div class="modal-content" style="border-radius: 0px;">
        <div class="modal-header" style="padding: 5px">
            <div style="width: 20px;float: right;margin-top: -10px;"> <i style="margin-top: 5px;cursor: pointer;" data-dismiss="modal" class="fa fa-times fa-2x close" aria-hidden="true"></i></div>
            <h4 class="modal-title text-uppercase ng-binding"><b>Hướng dẫn sử dụng
                
            </b></h4>
        </div>
        <div class="modal-body" style="padding: 0">
            <div class="row" style="background:#EFEFEF;">
                <div class="col-md-12" style="padding: 0; min-height: 100px;">
                    <?php
                    if (isset($_SESSION['CUSTOMER'])) {
                        ?>
                        <iframe width="100%" height="600px"  src="<?= url('pdf-viewer/web/viewer.php?urlFile=') ?>"></iframe>    
                        <?php
                    } else {
                        ?>
                        Xin mời bạn <a style="font-weight: bold;" href="<?= url('/login') ?>">đăng nhập </a> để xem tài liệu
                        <?php
                    }
                    ?>
                </div>
            </div> 
        </div>

    </div>
    <!-- Hien thi modal -->
    <div class="modal fade" id="modalDialog" role="dialog" data-backdrop="static">
    </div>
</form>
<style>
    .list-group-item{
        list-style: none;
        border: none;
    }
    .nav-pills>li.active>a{
        font-weight: bold;
    }
    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{
        border-radius: 0px;
        color: #337ab7;
        background-color: white!important;
    }
</style>
