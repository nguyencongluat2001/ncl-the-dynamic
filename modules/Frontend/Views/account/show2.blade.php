<style>
    .dap-an-dung {
        color: #00ff4c;
    }

    .dap-an {
        color: red;
    }

    .table-question tr td:first-child {
        width: 3rem;
        vertical-align: top;
    }

    input[type=radio] {
        margin-right: 5px;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content card">
        <div class="modal-header">
            <h5 class="modal-title">Chi tiết bài thi</h5>
            <button type="button" class="btn btn-sm btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <h6>I. THÔNG TIN ĐỐI TƯỢNG</h6>

            <div class="row px-5">
                <table class="table">
                    <tr>
                        <td>Họ và tên:</td>
                        <td style="border-right-width: 1px;">{{ $exam->doi_tuong_ho_ten }}</td>
                        <td>Địa chỉ Email:</td>
                        <td>{{ $exam->doi_tuong_email }}</td>
                    </tr>
                    <tr>
                        <td>Thời điểm nộp bài:</td>
                        <td style="border-right-width: 1px;">{{ $exam->thoi_diem_nop_bai }}</td>
                        <td>Thời gian làm bài:</td>
                        <td>{{ $exam->thoi_gian_lam_bai }} giây</td>
                    </tr>
                    <tr>
                        <td>Điểm đạt được:</td>
                        <td style="border-right-width: 1px;">{{ $exam->diem }} - ({{ $exam->so_dap_an_dung }}/10 câu
                            đúng)</td>
                        <td>Số người dự đoán:</td>
                        <td>{{ $exam->du_doan_so_nguoi ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Đơn vị:</td>
                        <td colspan="3">{{ $exam->doi_tuong_don_vi }}</td>
                    </tr>
                </table>
            </div>


            <h6 class="mt-3">II. THÔNG TIN CÂU TRẢ LỜI</h6>

            <div class="row">
                @foreach ($questions as $key => $value)
                    <span style="padding-top:10px;font-weight: 600;">Câu {{ $key + 1 }}:
                        {{ $value->ten_cau_hoi }}
                        <span>(Đáp án đúng: {{ $value->answer_correct }})</span>
                    </span>

                    <div class="px-5">
                        <table class="table-question">
                            @foreach ($value->questions as $k => $ques)
                                <tr>
                                    <td>
                                        <input type="radio" readonly onclick="event.preventDefault()"
                                            name="cau_hoi_{{ $value['thu_tu'] }}"
                                            {{ isset($ques['selected']) ? $ques['selected'] : '' }}>
                                        <span>{{ $ques['answer_order'] }}.</span>
                                    </td>
                                    <td>
                                        <span>{{ $ques['answer_content'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                Đóng
            </button>
        </div>
    </div>
</div>
</div>
