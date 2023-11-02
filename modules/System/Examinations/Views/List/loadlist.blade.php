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
            <col width="20%">
            <col width="20%">
            <col width="25%">
            <col width="5%">
            <col width="10%">
        </colgroup>    
        <thead>
            <tr>
            <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <!-- <td align="center"><i class="fas fa-pen-alt"></i></td> -->
                <td align="center"><b>STT</b></td>
                <td align="center"><b>Tên đợt thi</b></td>
                <td align="center"><b>Thời gian bắt đầu</b></td>
                <td align="center"><b>Thời gian kết thúc</b></td>
                <td align="center"><b>Tình trạng</b></td>
                <td align="center"><b>Trạng thái</b></td>
                <td align="center"><b>#</b></td>
            </tr>
        </thead>
        <tbody id="body_data">
            @if(count($datas) > 0)
                @foreach ($datas as $key => $value)
                <!-- @php $id = $value['id']; $i = 1; @endphp -->
                <tr>
                    <td align="center"><input type="checkbox" ondblclick=""
                            onclick="{select_checkbox_row(this);}" name="chk_item_id"
                            value="{{ $value['id'] }}"></td>
                    <td align="center" >{{ $key + 1 }}</td>
                    <td align="center"  onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'code_category')">
                        <span>{{ $value['ten'] }}</span>
                    </td>
                    <td align="center"   onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'name_category')">
                        <span>{{ date('d/m/Y', strtotime($value['ngay_bat_dau'])) }}</span>
                    </td>
                    <td align="center"  onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'decision')">
                        <span>{{ date('d/m/Y', strtotime($value['ngay_ket_thuc'])) }}</span>
                    </td>
                    <td align="center"  onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'decision')">
                        <span>
                            {{--<?php dump($value['ngay_bat_dau'] , date('Y-m-d').' 00:00:00.000'); ?>--}}
                            @if($value['ngay_bat_dau'] < date('Y-m-d').' 00:00:00.000' && date('Y-m-d').' 00:00:00.000' < $value['ngay_ket_thuc'])
                            Chưa diễn ra
                            @elseif($value['ngay_bat_dau'] <= date('Y-m-d').' 00:00:00.000' && date('Y-m-d').' 00:00:00.000' <= $value['ngay_ket_thuc'])
                            Đang diễn ra
                            @elseif($value['ngay_bat_dau'] > date('Y-m-d').' 00:00:00.000')
                            Chưa diễn ra
                            @else
                            Đã kết thúc
                            @endif
                        </span>
                    </td>
                    <td onclick="{select_row(this);}" ondblclick="click2('{{$id}}', 'order')">
                        <span>
                            @if($value['trang_thai'] ==1)
                            Hoạt động
                            @else
                            Không hoạt động
                            @endif</span>
                    </td>
                    <td align="center"><span style="color:#4200dd;font-weight: 600;" onclick="JS_Examinations.question('{{ $value['id'] }}')">
                    <button style="color:#0e2b5f;margin-bottom:0rem !important" class="btn btn-warning btn-sm shadow-sm" id="btn_add" type="button">
                        <i class="fas fa-comments"></i> Câu hỏi
                    </button></span></td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <tfoot>
        {{--@if(!empty($data) && count($data) > 0)
        <tr class="fw-bold" id="pagination">
            <td colspan="10">{{$data->links('pagination.default')}}</td>
        </tr>
        @else
        <tr id="pagination">
            <td colspan="10">Không tìm thấy dữ liệu!</td>
        </tr>
        @endif--}}
    </tfoot>
</div>
