<form action="index" method="POST" id="frmCommentIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="FK_ARTICLE" id="FK_ARTICLE" value="{{ $FK_ARTICLE }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/album-images") }}', 'list');"><i class="fa fa-list"></i> Danh sách phản hồi</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row form-group input-group-index">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <input name="search" class="form-control" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary  search" id="btn_search" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-group input-group-index">
                    <div class="pull-right">
                        <button class="btn btn-success " id="btn_see_comment" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.edit')}}"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fa fa-trash-o"></i></button>
                        <button class="btn btn-basic " id="close_modal">Quay lại</button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col width='5%'/>
                            <col width='15%'/>
                            <col width='15%'/>
                            <col width='55%'/>
                            <col width='10%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                                </td>
                                <td align="center">
                                    <b>Người gửi</b>
                                </td>
                                <td align="center">
                                    <b>Ngày gửi</b>
                                </td>
                                <td align="center">
                                    <b>Nội dung</b>
                                </td>
                                <td align="center">
                                    <b>Trạng thái</b>
                                </td>
                            </tr>
                            <tr/>
                        </thead>
                        <tbody id="data-list-comment">

                        </tbody>
                    </table>
                </div>
                <!-- Phân trang dữ liệu -->
                <div class="row" id="pagination"></div>
            </div>
        </div>
    </section>
</form>
<div class="modal-content" id="modalRelateImages">

</div>
<style>
    #table-mo-ta tr td{
        padding: 3px;
    }
    #table-mo-ta {
        width: 100%;
    }
</style>

