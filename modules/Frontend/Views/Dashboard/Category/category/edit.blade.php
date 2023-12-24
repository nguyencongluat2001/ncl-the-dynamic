<form id="frmAddCategory" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{isset($_id) ? $_id : ''}}">
    <div class="modal-dialog modal-lg">
    <div class="modal-content  card">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật thể loại</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                    X
                </button>
            </div>
            <div class="modal-body">
                <!-- Mã danh mục -->
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Mã danh mục</span>
                    <div class="col-md-4">
                        <select class="form-control input-sm chzn-select" name="cate"
                            id="cate">
                            <option value=''>-- Chọn danh mục --</option>
                            @if(!empty($cates))
                                @foreach($cates as $item)
                                    <option 
                                    @if((isset($datas) && $item->code_cate == $datas->cate) || (isset($dataCate) && $dataCate == $item->code_cate)) selected @endif
                                    value="{{$item['code_cate']}}">{{$item['name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                {{--  Mã thể loại --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Mã thể loại</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->code_category) ? $datas->code_category : ''}}" name="code_category" id="code_category"
                            placeholder="Nhập mã thể loại..." />
                    </div>
                </div>
                {{-- Tên thể loại --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Tên thể loại</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->name_category) ? $datas->name_category : ''}}" name="name_category" id="name_category"
                            placeholder="Nhập tên thể loại..." />
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
                {{--  Số thứ tự --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Số thứ tự</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->order) ? $datas->order : $order}}" name="order" id="order"
                            placeholder="Nhập mô tả..." />
                    </div>
                </div>
                {{-- trạng thái --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Trạng thái</span>
                    <div class="col-md-8">
                        <input type="checkbox" name="status" id="status" {{isset($datas->status) && $datas->status == 1 ? 'checked' : ''}}/>
                        <span for="is_checkbox_status">Hoạt động</span> <br>
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
