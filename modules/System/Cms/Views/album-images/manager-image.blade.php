<form action="index" method="POST" id="frmImagesIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="FK_ALBUM_IMAGE" id="FK_ALBUM_IMAGE" value="{{ $FK_ALBUM_IMAGE }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/album-images") }}', 'list');"><i class="fa fa-list"></i> Danh sách ảnh</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row form-group input-group-index">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <input name="search_child" class="form-control" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary  search" id="btn_search" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row form-group input-group-index">
                    <div class="pull-right">
                        <button class="btn btn-primary " id="btn_add" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.add')}}"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-success " id="btn_edit" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.edit')}}"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fa fa-trash-o"></i></button>
                        <button class="btn btn-basic " id="close_modal">Quay lại</button>
                    </div>
                </div>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col width='5%'/>
                            <col width='25%'/>
                            <col width='55%'/>
                            <col width='15%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b>
                                </td>
                                <td align="center">
                                    <b>Hình ảnh</b>
                                </td>
                                <td align="center">
                                    <b>Mô tả</b>
                                </td>
                                <td align="center">
                                    <b>Tình trạng</b>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="data-list-images">

                        </tbody>
                    </table>
                </div>
                <!-- Phân trang dữ liệu -->
                <div class="row" id="pagination1"></div>
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

