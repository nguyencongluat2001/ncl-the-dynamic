@extends('System.layouts.index')
@section('content')
<!-- /.content --> 
<style>
    .row{
        margin-bottom: 5px;
    }
</style>
<form action="index" method="POST" id="frmReportsIndex">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button" onclick="LoadModul('{{ url("system/reports") }}', 'list');"><i class="fa fa-list"></i> Chi tiết báo cáo</a></li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-7" style="padding: 0;text-align: right;padding-top: 5px">
                        <label class="control-label">Loại báo cáo:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="report_type" name="report_type" class="form-control input-sm chzn-select" onchange="location = this.value;">
                            <option value="{{ url('system/cms/report/detail') }}">Báo cáo chi tiết theo từng đơn vị</option>
                           <option value="{{ url('system/cms/reports') }}">Báo cáo tổng quan</option>
                        </select>
                    </div>
                </div>
             
                <!-- Màn hình danh sách -->
                <div class="row" id="table-container" style="margin-top: 30px;">
                    <table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">
                        <colgroup>
                            <col width='10%'/>
                            <col width='60%'/>
                            <col width='30%'/>
                        </colgroup>
                        <thead class="thead-inverse">
                            <tr class="header">
                                <td align="center">
                                    <b>STT</b>
                                </td>
                                <td align="center">
                                    <b>Chuyên mục</b>
                                </td>
                                <td align="center">
                                    <b>Số lượng</b>
                                </td>
                            </tr>
                            <tr/>
                        </thead>
                        <tbody id="a">
                            @php
                                $i = 1;
                            @endphp
                           @foreach ($total as $item)
                               <tr>
                                    <td align="center">{{ $i++ }}</td>
                                   <td align="center">{{ $item->C_NAME }}</td>
                                   <td align="center">{{ $item->C_QUANTITY }}</td>
                               </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</form>
<!-- Hien thi modal -->
</div>
<script type="text/javascript">
    $('.chzn-select').chosen();
</script>
@endsection

