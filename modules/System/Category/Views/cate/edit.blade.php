<form id="frmAdd" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{!empty($datas->id) ? $datas->id : ''}}">
    <div class="modal-dialog modal-lg">
    <div class="modal-content card">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật danh mục</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                    X
                </button>
            </div>
            <div class="modal-body">
                {{-- Mã --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Mã danh mục</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->code_cate) ? $datas->code_cate : ''}}" name="code_cate" id="code_cate"
                            placeholder="Nhập mã danh mục..." />
                    </div>
                </div>
                {{-- Tên --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Tên danh mục</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->name) ? $datas->name : ''}}" name="name" id="name"
                            placeholder="Nhập tên danh mục..." />
                    </div>
                </div>
                {{--  Mô tả --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Mô tả</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->decision) ? $datas->decision : ''}}" name="decision" id="decision"
                            placeholder="Nhập mô tả..." />
                    </div>
                </div>
                {{--  Thứ tự --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Thứ tự</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->order) ? $datas->order : ''}}" name="order" id="order"
                            placeholder="Nhập thứ tự..." />
                    </div>
                </div>
                {{-- trạng thái --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Trạng thái</span>
                    <div class="col-md-8">
                        <input type="checkbox" name="status" id="status" {{isset($datas->status) && $datas->status == 1 ? 'checked' : ''}}/>
                        <label for="status">Hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="btupdate">
                        <button id='btn_create' class="btn btn-primary btn-sm" type="button">
                            Cập nhật
                        </button>
                    </span>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
