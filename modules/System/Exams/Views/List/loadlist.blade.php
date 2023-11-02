<style>
    .unit-edit span {
        font-size: 19px;
    }
    
</style>
@php
    use Modules\System\ListType\Models\ListModel;
@endphp
<script type="text/jscript"  src="{{ asset('/resources/js/assets/CoreTable.js') }}"></script>

<div class="table-responsive pmd-card pmd-z-depth ">
    <table id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <colgroup>
            <col width="5%">
            <col width="5%">
            <col width="20%">
            <col width="20%">
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="5%">
            <col width="5%">

        </colgroup>    
        <thead>
            <tr>
            <td align="center" style=" white-space: inherit;vertical-align: middle;"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <!-- <td align="center" style=" white-space: inherit;vertical-align: middle;"><i class="fas fa-pen-alt"></i></td> -->
                <td align="center" style="vertical-align: middle;"><b>STT</b></td>
                <td align="center" style="vertical-align: middle;"><b>Họ và tên</b></td>
                <td align="center" style="vertical-align: middle;"><b>Đơn vị</b></td>
                <td align="center" style="vertical-align: middle;"><b>Nộp bài lúc</b></td>
                <td align="center" style="vertical-align: middle;"><b>Thời gian <br> làm bài</b></td>
                <td align="center" style="vertical-align: middle;"><b>Kết quả</b></td>
                <td align="center" style="vertical-align: middle;"><b>Dự đoán</b></td>
                <td align="center" style="vertical-align: middle;"><b>#</b></td>
            </tr>
        </thead>
        <tbody id="body_data">
            @if(count($datas) > 0)
                @foreach ($datas as $key => $value)
                <!-- @php $id = $value['id']; $i = 1; @endphp -->
                <tr>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center"><input type="checkbox" ondblclick=""
                            onclick="{select_checkbox_row(this);}" name="chk_item_id"
                            value="{{ $value['id'] }}"></td>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center" >{{ $key + 1 }}</td>
                    <td style=" white-space: inherit;vertical-align: middle;"  onclick="{select_row(this);}">
                        <span>{{ $value['doi_tuong_ho_ten'] }}</span>
                    </td>
                    <td style=" white-space: inherit;vertical-align: middle;"   onclick="{select_row(this);}">
                         <span>
                            <?php $donvi = ListModel::where('code',$value['doi_tuong_don_vi'])->first(); ?>
                            {{  !empty($donvi->name)?$donvi->name:'' }}
                        </span>
                    </td>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center"   onclick="{select_row(this);}">
                        <span>{{ date('H:i:s', strtotime($value['thoi_diem_nop_bai'])) }} <br> {{ date('d/m/Y', strtotime($value['thoi_diem_nop_bai'])) }}</span>
                    </td>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center"  onclick="{select_row(this);}">
                        <span>{{ $value['thoi_gian_lam_bai'] }} s</span>
                    </td>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center"  onclick="{select_row(this);}">
                        <span>{{ $value['diem'] }} điểm - ({{ $value['so_dap_an_dung'] }}/10)</span>
                    </td>
                    <td style=" white-space: inherit;vertical-align: middle;" align="center"  onclick="{select_row(this);}">
                        <span>{{ $value['du_doan_so_nguoi'] }}</span>
                    </td>
                    <td align="center" style="white-space: inherit;vertical-align: middle;"><span style="color:#4200dd;font-weight: 600;" onclick="JS_Exams.show('{{ $value['id'] }}')"><i class="fas fa-eye"></i></span></td>
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
