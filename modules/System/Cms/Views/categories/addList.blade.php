<style>
    #frmAddCategories #table-data tr td {
        border: none;
    }

    input[readonly] {
        cursor: not-allowed;
    }

    #category_type_chosen,
    #layout_chosen {
        width: 80% !important;
    }
</style>
<form id="frmAddListType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input class="form-control" name="listtype_id" type="hidden" value="{{ $listtype->id ?? '' }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CẬP NHẬT DANH MỤC</h5>
                <button type="button" class="btn-close close-list"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <label class="col-md-3 control-label required">Mã đối tượng</label>
                        <div class="col-md-6">
                            <input class="form-control input-md" type="text" id="code" name="code" value="" xml_data="false" column_name="code">
                        </div>
                    </div>
                    <div class=" row form-group">
                        <label class="col-md-3 control-label required">Tên đối tượng</label>
                        <div class="col-md-6">
                            <input class="form-control input-md" type="text" id="name" name="name" value="" xml_data="false" column_name="name">
                        </div>
                    </div>
                    <div class=" row form-group">
                        <label class="col-md-3 control-label">Ghi chú</label>
                        <div class="col-md-6">
                            <textarea class="form-control" id="note_list" name="note_list" rows="3" style="width:60%" xml_data="true" xml_tag_in_db="note_list"></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-3 control-label required">Thứ tự hiển thị</label>
                        <div class="col-md-6">
                            <input class="form-control input-md" type="text" id="order" name="order" value="{{ $order ?? '' }}" xml_data="false" column_name="order" style="width:20%">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-3 control-label">Trạng thái</label>
                        <div class="col-md-6">
                            <div class="input-group checkbox">
                                <label><input type="checkbox" checked id="status" name="status" xml_data=" false" column_name="status">Hoạt động</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_updateAddList" class="btn btn-primary btn-flat" type="button">Cập nhật và thêm tiếp</button>
                <button id="btn_updateList" class="btn btn-primary btn-flat" type="button">Cập nhật</button>
                <button type="button" class="btn btn-default close-list">Đóng</button>
            </div>
        </div>
    </div>
</form>