<style>
    .unit-edit span {
        font-size: 19px;
    }
</style>
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
        </colgroup>     -->
        <thead>
            <tr>
                <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Tên</b></td>
                <td align="center"><b>Email</b></td>
                <td align="center"><b>Phone</b></td>
                <td align="center"><b>Nơi ở</b></td>
                <td align="center"><b>Trạng thái</b></td>
                <td align="center"><b><span onclick="JS_BangCap.addrow()" class="text-cursor text-primary"><i
                                class="fas fa-plus-square"></i></span></b></td>
            </tr>
        </thead>
        <tbody id="body_data">
            @if (count($datas) > 0)
                @foreach ($datas as $key => $data)
                    @php
                        $id = $data->id;
                    @endphp
                    <tr>
                        <td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}"
                                name="chk_item_id" value="{{ $data->id }}"></td>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td class="td_code_category_{{ $id }}" onclick="{select_row(this);}"
                            ondblclick="click2('{{ $id }}', 'name')">
                            <span id="span_code_category_{{ $id }}"
                                class="span_code_category_{{ $id }}">{{ $data->name }}</span>
                        </td>
                        <td class="td_name_category_{{ $id }}" onclick="{select_row(this);}"
                            ondblclick="click2('{{ $id }}', 'email')">
                            <span id="span_name_category_{{ $id }}"
                                class="span_name_category_{{ $id }}">{{ $data->email }}</span>
                        </td>
                        <td class="td_decision_{{ $id }}" onclick="{select_row(this);}"
                            ondblclick="click2('{{ $id }}', 'phone')">
                            <span id="span_decision_{{ $id }}"
                                class="span_decision_{{ $id }}">{{ $data->phone }}</span>
                        </td>
                        <td class="text-center td_order_{{ $id }}" onclick="{select_row(this);}"
                            ondblclick="click2('{{ $id }}', 'address')">
                            <span id="span_order_{{ $id }}"
                                class="span_order_{{ $id }}">{{ $data->address }}</span>
                        </td>
                        <td onclick="{select_row(this);}" align="center">
                            <label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">
                                <input type="checkbox" hidden class="custom-control-input toggle-status"
                                    id="status_{{ $id }}" data-id="{{ $id }}"
                                    {{ $data->trang_thai == 1 ? 'checked' : '' }}>
                                <span class="custom-control-indicator p-0 m-0"
                                    onclick="JS_BangCap.changeStatusBangCap('{{ $id }}')"></span>
                            </label>
                        </td>
                        <td style="width:5% ;vertical-align: middle;" align="center">
                            <button onclick="JS_BangCap.infoBangCap('{{ $data['id'] }}')" class="btn btn-light"
                                type="button">
                                <i style="color:#00740a" class="far fa-eye"></i>
                            </button>
                            <button onclick="JS_BangCap.deleteBangCap('{{ $data['id'] }}')" class="btn btn-light"
                                type="button">
                                <i style="color:#00740a"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                    </svg></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <tfoot>
        @if (count($datas) > 0)
            <tr class="fw-bold" id="pagination">
                <td colspan="10">{{ $datas->links('pagination.phantrang') }}</td>
            </tr>
        @else
            <tr id="pagination">
                <td colspan="10">Không tìm thấy dữ liệu!</td>
            </tr>
        @endif
    </tfoot>
</div>
