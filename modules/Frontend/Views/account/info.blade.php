@extends('Frontend::account.layout.index')

@section('script-child')
    <script type="text/javascript">
        $('#account_sidebar_info').addClass('active');
        $('#account_sidebar_history').removeClass('active');

        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        EfyLib.loadFileJsCss(arrJsCss);

        var jsInfo = new Info('{{ url('') }}', 'dang-nhap');
        jQuery(document).ready(function($) {
            jsInfo.loadIndex();
        })
    </script>
@endsection

@section('content-child')
    <div class="container">
        <h3 class="account-title">Thông tin tài khoản</h3>
        <form id="frm_info">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" id="current_unit" disabled value="{{ $_SESSION['hoithicchc']['unit'] }}">
                    <table class="table table-hover table-responsive">
                        <colgroup>
                            <col style="width: 15rem">
                            <col>
                        </colgroup>
                        <tr>
                            <td>Họ tên</td>
                            <td>{{ $_SESSION['hoithicchc']['name'] }}</td>
                        </tr>
                        <tr>
                            <td>Số CMND/CCCD</td>
                            <td>{{ $_SESSION['hoithicchc']['cmnd'] }}</td>
                        </tr>
                        <tr>
                            <td>Email công vụ</td>
                            <td>{{ $_SESSION['hoithicchc']['email'] }}</td>
                        </tr>
                        <tr>
                            <td>Cấp đơn vị</td>
                            <td>
                                <span id="cap_don_vi_text"></span>
                                <select class="form-select form-select-sm chzn-select hide" id="cap_don_vi" name="cap_don_vi">
                                    <option @if (isset($dataUnitUser) && $dataUnitUser == 'SO_NGANH') selected @endif value="SO_NGANH">
                                        Sở - ngành
                                    </option>
                                    <option @if (isset($dataUnitUser) && $dataUnitUser == 'QUAN_HUYEN') selected @endif value="QUAN_HUYEN">
                                        Quận - huyện
                                    </option>
                                    <option @if (isset($dataUnitUser) && $dataUnitUser == 'PHUONG_XA') selected @endif value="PHUONG_XA">
                                        Phường - xã
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Đơn vị</td>
                            <td>
                                <span id="don_vi_text"></span>
                                <select class="form-select form-select-sm chzn-select hide" id="don_vi" name="don_vi">
                                    <option value="">Chưa chọn cấp đơn vị</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col d-flex" style="justify-content: flex-end">
                            <button type="button" class="btn btn-primary ms-1" id="btn_edit">Sửa</button>
                            <button type="button" class="btn btn-primary ms-1 hide" id="btn_update">
                                Cập nhật
                            </button>
                            <button type="button" class="btn btn-danger ms-1 hide" id="btn_cancel">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
