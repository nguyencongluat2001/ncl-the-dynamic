@extends('system.layouts_Backend.index')
@section('body')
<script type="text/javascript" src="{{ URL::asset('dist/js/backend/pages/JS_Sql.js') }}"></script>
<div class="container-fluid">
    <div class="row">
        <form action="" method="POST" id="frmFaq_index">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <section class="content-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row form-group">
                            <!-- <div class="col-md-3">
                                <button class="btn btn-success shadow-sm" id="btn_add" type="button" data-toggle="tooltip" data-original-title="Thêm"><i class="fas fa-plus"></i></button>
                                <button class="btn btn-danger shadow-sm" id="btn_delete" type="button" data-toggle="tooltip" data-original-title="Xóa"><i class="fas fa-trash-alt"></i></button>
                            </div> -->
                            <div class="input-group" style="width:90%;height:10%">
                                <input id="search" name="search" type="text" class="form-control" placeholder="Câu lệnh sql...">
                            </div>
                            <button style="width:5%" id="txt_search" name="txt_search" type="button" class="btn btn-dark"><i class="fas fa-search"></i></button>

                        </div>
                        <div id="table-container" style="padding-top:10px"></div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
<div class="modal fade" id="addmodal" role="dialog" data-backdrop="static"></div>
<script type="text/javascript">
    var baseUrl = "{{ url('') }}";
    var JS_Sql = new JS_Sql(baseUrl, 'system', 'sql');
    jQuery(document).ready(function($) {
        JS_Sql.loadIndex(baseUrl);
    })
</script>
@endsection