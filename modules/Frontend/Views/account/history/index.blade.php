@extends('Frontend::account.layout.index')

@section('script-child')
    <script type="text/javascript">
        $('#account_sidebar_info').removeClass('active');
        $('#account_sidebar_history').addClass('active');

        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib .loadFileJsCss(arrJsCss);

        var jsHistory = new History('{{ url('') }}', 'tai-khoan');
        jQuery(document).ready(function($) {
            jsHistory.loadIndex();
        })
    </script>
@endsection

@section('content-child')
    <form id="frm_history">
        <div class="container-fluid">
            <h3 class="account-title">Lịch sử bài thi</h3>

            <div class="mt-3">
                <!-- Lọc -->
                <div class="row">
                    <div class="col-md-2 text-end">
                        <label for="">Hội thi năm</label>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="nam" name="nam">
                            @foreach ($arrYear as $year)
                            <option value="{{ $year->code }}" @if ($year->code === date('Y')) selected @endif>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-md">
                            <input type="text" id="search_text" name="search" class="form-control"
                                placeholder="Nhập từ khóa tìm kiếm">
                            <button type="button" id="btn_search" class="input-group-text d-block search"
                                style="right: 0; z-index: 5;">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container"></div>
            </div>
        </div>
    </form>

    <!-- Hien thi modal -->
    <div class="modal fade" id="modal1" role="dialog"></div>
@endsection
