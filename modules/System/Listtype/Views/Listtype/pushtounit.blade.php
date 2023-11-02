<style>
    TABLE.griddata {
        color: #000000;
        cursor: pointer;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        width: 100%;
    }

    table {
        border: 1px solid #dddddd;
        border-collapse: collapse;
        border-spacing: 0;
    }

    TABLE.griddata TR TD {
        border-bottom: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        font-size: 13px;
        height: 40px;
        padding: 0 3px;
    }

    label {
        padding-top: 7px;
    }

    TD:first-child {
        border-left: 1px solid #dddddd;
    }

    TABLE.griddata tr.header TD {
        background-color: #aaaaaa;
        background-image: linear-gradient(#ffffff, #f5f5f5);
        border-bottom: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-top: medium none !important;
        color: #464343;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        font-weight: bold;
        height: 28px;
        line-height: 28px;
        padding-left: 10px
    }

    div#form-content .textbox {
        border-radius: 3px;
    }

    .requiein {
        color: red;
    }
</style>
<form id="frmpushtounit" role="form" action="" method="POST">
    <input type="hidden" name="_token" id='_token' value="{{ csrf_token() }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ĐẨY DỮ LIỆU THỦ TỤC VỀ CÁC ĐƠN VỊ</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3"><label>Chọn đơn vị cần xuất (<span class="requiein">*</span>)</label></div>
                    <div class="col-md-9">
                        <ul class="nav nav-tabs" id="tabUrl">
                            <li class="active"><a data-toggle="tab" href="#menu1">Quận huyện</a></li>
                            <li><a data-toggle="tab" href="#menu2">Sở nghành</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Thông tin cơ bản -->
                            <div id="menu1" class="tab-pane fade in active">
                                {!!$htmls!!}
                            </div>

                            <!-- Thông chi tiết -->
                            <div id="menu2" class="tab-pane fade">
                                {!!$htmlsDepartment!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_savepushtounit' class="btn btn-primary" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>

<script>
    function checkallper(obj, name) {
            if (obj.checked) {
                $('input[name="' + name + '"]').prop('checked', true);
            } else {
                $('input[name="' + name + '"]').prop('checked', false);
            }
        }
</script>