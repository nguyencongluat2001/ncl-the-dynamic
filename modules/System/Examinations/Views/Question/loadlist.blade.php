<style>
    .unit-edit span {
        font-size: 19px;
    }
    
</style>
{{-- @php
use Modules\System\Recordtype\Helpers\WorkflowHelper;
@endphp --}}
<script type="text/jscript"  src="{{ asset('/resources/js/assets/CoreTable.js') }}"></script>

<div class="table-responsive pmd-card pmd-z-depth ">
    <table id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <colgroup>
            <col width="5%">
            <col width="5%">
            <col width="70%">
            <col width="10%">
            <col width="5%">
            <col width="5%">
        </colgroup>    
        <thead>
            <tr>
            <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <!-- <td align="center"><i class="fas fa-pen-alt"></i></td> -->
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Tên câu hỏi</b></td>
                <td align="center"><b>Phương án đúng</b></td>
                <td align="center"><b>Trạng thái</b></td>
                <td align="center"><b>#</b></td>
            </tr>
        </thead>
        <tbody id="body_data">
            @if(count($datas) > 0)
                @foreach ($datas as $key => $value)
                <!-- @php $id = $value['id']; $i = 1; @endphp -->
                <tr>
                    <td align="center" style="white-space: inherit;vertical-align: middle;"><input type="checkbox" ondblclick=""
                            onclick="{select_checkbox_row(this);}" name="chk_item_id"
                            value="{{ $value['id'] }}"></td>
                    <td align="center" style="white-space: inherit;vertical-align: middle;">{{ $key + 1 }}</td>
                    <td style="display: -webkit-box;-webkit-box-orient: vertical;white-space: break-spaces;overflow:hidden;"  onclick="{select_row(this);}">
                        {!!$value['ten_cau_hoi'] !!}
                    </td>
                    <td align="center" style="white-space: inherit;vertical-align: middle;"  onclick="{select_row(this);}">
                        <span>{{ $value['dap_an_dung'] }}</span>
                    </td>
                    <td align="center" style="white-space: inherit;vertical-align: middle;" onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'order')">
                        <span>
                            @if($value['trang_thai'] ==1)
                            Hoạt động
                            @else
                            Không hoạt động
                            @endif</span>
                    </td>
                    <td align="center" style="white-space: inherit;vertical-align: middle;"><span style="color:#4200dd;font-weight: 600;" onclick="JS_Questions.show('{{ $value['id'] }}')"><i class="fas fa-eye"></i></span></td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <tfoot>
        @if(!empty($datas) && count($datas) > 0)
        <tr class="fw-bold" id="pagination">
            <td colspan="10">{{$datas->links('pagination.default')}}</td>
        </tr>
        @else
        <tr id="pagination">
            <td colspan="10">Không tìm thấy dữ liệu!</td>
        </tr>
        @endif
    </tfoot>
</div>
