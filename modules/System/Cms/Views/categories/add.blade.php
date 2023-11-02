<style>
    #frmAddCategories #table-data tr td{
        border: none;
    }
    input[readonly]{
        cursor: not-allowed;
    }
    #category_type, #layout{
        display: inline-block;
    }
    #category_type_chosen, #layout_chosen, #category_type, #layout{
        width: 80% !important;
    }
</style>
<form id="frmAddCategories" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input class="form-control" name="parent_id" type="hidden" value="{{ $parent_id ?? '' }}">
    <input class="form-control" name="id" type="hidden" value="{{ $id ?? '' }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CẬP NHẬT THÔNG TIN CHUYÊN MỤC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="table-data">
                    <colgroup>
                        <col width="20%">
                        <col width="30%">
                        <col width="20%">
                        <col width="30%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td align="right"><span class="radio control-label">Thuộc chuyên mục</span></td>
                            <td colspan="3"><input readonly class="form-control input-md" type="text" value="{!! $unitparent !!}"></td>
                        </tr>
                        <tr>
                            <td align="right"><span class="radio control-label required">Tên chuyên mục</span></td>
                            <td><input class="form-control input-md" type="text" id="name" name="name" value="{{ $data['name'] ?? '' }}" xml_data="false"></td>
                            <td align="right"><span class="radio control-label required">ID chuyên mục</span></td>
                            <td><input class="form-control input-md" type="text" id="id_menu" name="id_menu" value="{{ $data['id_menu'] ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td align="right"><span class="radio control-label required">URL</span></td>
                            <td colspan="3"><input class="form-control input-md" type="text" id="slug" name="slug" value="{{ $data['slug'] ?? '' }}" xml_data="false"></td>
                        </tr>
                        <tr>
                            <td align="right"><span class="radio control-label required">Layout</span></td>
                            <td>
                                <select class="form-control chzn-select" id="layout" name="layout">
                                    <option value="">---Chọn Layout---</option>
                                    @if(isset($layouts) && count($layouts) > 0)
                                    @foreach($layouts as $layout)
                                    <option {{ isset($data['layout']) && $data['layout'] == $layout['code'] ? 'selected' : '' }} value="{{ $layout['code'] }}">{{ $layout['name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <button id="addLayout" type="button" class="btn btn-primary mb-0 pe-3 ps-3"><i class="fas fa-plus"></i></button>
                            </td>
                            <td align="right"><span class="radio control-label">Loại chuyên mục</span></td>
                            <td>
                                <select class="form-control chzn-select" id="category_type" name="category_type">
                                    <option value="">---Chọn loại chuyên mục---</option>
                                    @if(isset($category_type) && count($category_type) > 0)
                                    @foreach($category_type as $type)
                                    <option {{ isset($data['category_type']) && $data['category_type'] == $type['code'] ? 'selected' : '' }} value="{{ $type['code'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <button id="addCategoryType" type="button" class="btn btn-primary mb-0 pe-3 ps-3"><i class="fas fa-plus"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><span class="radio control-label">Icon</span></td>
                            <td><input class="form-control input-md" type="text" id="icon" name="icon" value="{{ $data['icon'] ?? '' }}" xml_data="false"></td>
                            <td></td>
                            <td>
                                <label><input style="margin-top: 10px;" type="checkbox" id="is_display_at_home" name="is_display_at_home" 
                                        {{ isset($data['is_display_at_home']) && $data['is_display_at_home'] == 1 ? 'checked' : '' }} > Hiển thị trên trang chủ</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><span class="radio control-label">Thứ tự</span></td>
                            <td><input class="form-control input-md" type="text" id="order" name="order" value="{{ $data['order'] ?? ($order ?? '') }}" xml_data="false"></td>
                            <td></td>
                            <td><label><input style="margin-top: 10px;" {{ $checked ?? '' }} type="checkbox" id="status" name="status"> Trạng thái</label></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btn_update" class="btn btn-primary btn-flat" type="button">Cập nhật</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
            </div>
        </div>
    </div>
</form>