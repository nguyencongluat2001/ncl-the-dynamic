<style>
    .unit-edit span {
        font-size: 19px;
    }
    td > p { overflow-y:scroll;overflow-x:hidden;} 
</style>
{{-- @php
use Modules\System\Recordtype\Helpers\WorkflowHelper;
@endphp --}}
<div class="table-responsive pmd-card pmd-z-depth ">
    <table id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <!-- <colgroup>
            <col width="5%">
            <col width="5%">
            <col width="20%">
            <col width="20%">
            <col width="25%">
            <col width="5%">
            <col width="10%">
            <col width="5%">
        </colgroup> -->
        <thead>
            <tr>
                <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <!-- <td align="center"><i class="fas fa-pen-alt"></i></td> -->
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Mã danh mục</b></td>
                <td align="center"><b>Tên</b></td>
                <td align="center"><b>Mô tả</b></td>
                <td align="center"><b>Sắp sếp</b></td>
                <td align="center"><b>Trạng thái</b></td>
                <td align="center"><b><span onclick="JS_Category.addrow()" class="text-cursor text-primary"><i class="fas fa-plus-square"></i></span></b></td>
            </tr>
        </thead>
        <tbody id="body_data">
            @if(count($datas) > 0)
                @foreach ($datas as $key => $data)
                @php $id = $data->id; $i = 1; @endphp
                    <tr>
                        <td align="center"><input type="checkbox" name="chk_item_id"
                                value="{{ $data->id }}"></td>
                        <td onclick="{select_row(this);}" align="center">{{ $key + 1 }}</td>
                        <td class="td_code_cate_{{$id}}" onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'code_cate')">
                            <span id="span_code_cate_{{$id}}" class="span_code_cate_{{$id}}">{{ $data->code_cate }}</span>
                        </td>
                        <td class="td_name_{{$id}}" onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'name')">
                            <span id="span_name_{{$id}}" class="span_name_{{$id}}">{{ $data->name }}</span>
                        </td>
                        <td class="td_decision_{{$id}}" onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'decision')">
                            <span id="span_decision_{{$id}}" class="span_decision_{{$id}}">{{ $data->decision }}</span>
                        </td>
                        <td class="text-center td_order_{{$id}}" onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'order')">
                            <span id="span_order_{{$id}}" class="span_order_{{$id}}">{{ $data->order }}</span>
                        </td>
                        <td onclick="{select_row(this);}" align="center">
                            <label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">
                                <input type="checkbox" hidden class="custom-control-input toggle-status" id="status_{{$id}}" data-id="{{$id}}" {{ $data->status == 1 ? 'checked' : '' }}>
                                <span class="custom-control-indicator p-0 m-0" onclick="JS_Category.changeStatusCate('{{$id}}')"></span>
                            </label>
                        </td>
                        <td align="center"><span class="text-cursor text-warning" onclick="JS_Category.edit('{{$id}}')"><i class="fas fa-edit"></i></span></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <tfoot>
        @if(count($datas) > 0)
        <tr class="fw-bold" id="pagination">
            <td colspan="10">{{$datas->links('pagination.phantrang')}}</td>
        </tr>
        @else
        <tr id="pagination">
            <td colspan="10">Không tìm thấy dữ liệu!</td>
        </tr>
        @endif
    </tfoot>
</div>
