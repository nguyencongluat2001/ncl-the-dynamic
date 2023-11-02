<style>
    .row{
        margin-bottom: 5px;
    }
</style>
<form action="index" method="POST" id="frmSearchDocuments">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tìm kiếm văn bản</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <input name="search" class="form-control" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary  search" onclick="JS_ArticlesBook.load_document()" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Màn hình danh sách -->
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col style="width: 5%"/>
                            <col style="width: 10%"/>
                            <col style="width: 45%"/>
                            <col style="width: 10%"/>
                            <col style="width: 10%"/>
                            <col style="width: 10%"/>
                            <col style="width: 10%"/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td style="text-align: center">
                                    <strong><input type="checkbox" name="chk_all_item_id"
                                                   onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></strong>
                                </td>
                                <td style="text-align: center">
                                    <strong>Mã ấn phẩm</strong>
                                </td>
                                <td style="text-align: center">
                                    <strong>Nhan đề</strong>
                                </td>
                                <td style="text-align: center">
                                    <strong>Tác giả</strong>
                                </td>
                                <td style="text-align: center">
                                    <strong>Nhà xuất bản</strong>
                                </td>
                                <td style="text-align: center">
                                    <strong>Tình trạng</strong>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="data-list">

                        </tbody>
                    </table>
                    <!-- Phân trang dữ liệu -->
                    <div class="row" id="pagination"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button  onclick="JS_ArticlesBook.submit_document('form#frmAddIndexAnnouncement')" class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>
