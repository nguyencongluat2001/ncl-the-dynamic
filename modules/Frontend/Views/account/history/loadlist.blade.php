<style>
    .unit-edit span {
        font-size: 19px;
    }
</style>
@php
    use Modules\System\Examinations\Models\ExaminationsModel;
@endphp
<script type="text/jscript"  src="{{ asset('/resources/js/assets/CoreTable.js') }}"></script>

<div class="table-responsive pmd-card pmd-z-depth ">
    <table id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <colgroup>
            <col width="5%">
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="5%">
        </colgroup>
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th class="text-center">Hội thi năm</th>
                <th class="text-center">Đợt thi</th>
                <th class="text-center">Nộp bài lúc</th>
                <th class="text-center">Thời gian làm bài</th>
                <th class="text-center">Kết quả</th>
                <th class="text-center">#</th>
            </tr>
        </thead>
        <tbody id="body_data">
            @if (count($datas) > 0)
                @foreach ($datas as $key => $value)
                    <tr>
                        <td class="text-center align-middle">
                            {{ $key + 1 }}
                        </td>
                        <td class="text-center align-middle">
                            <span>{{ $value->nam }}</span>
                        </td>
                        <td class="text-center align-middle">
                            <span>{{ $value->ten }}</span>
                        </td>
                        <td class="text-center align-middle">
                            <span>
                                {{-- {{ date('H:i:s', strtotime($value['thoi_diem_nop_bai'])) }} <br>
                                {{ date('d/m/Y', strtotime($value['thoi_diem_nop_bai'])) }} --}}
                                {{ $value->thoi_diem_nop_bai }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <span>{{ $value->thoi_gian_lam_bai }} s</span>
                        </td>
                        <td class="text-center align-middle">
                            <span>{{ $value->diem }} điểm - ({{ $value->so_dap_an_dung }}/10)</span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="show-exam" data-exam-id="{{ $value->id }}"
                                style="color:#4200dd;font-weight: 600;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{-- <tfoot>
        @if (!empty($datas) && count($datas) > 0)
        <tr class="fw-bold" id="pagination">
            <td colspan="10">{{$datas->links('pagination.default')}}</td>
        </tr>
        @else
        <tr id="pagination">
            <td colspan="10">Không tìm thấy dữ liệu!</td>
        </tr>
        @endif
    </tfoot> --}}
</div>
