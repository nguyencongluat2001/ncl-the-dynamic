<form id="frmAddFAQ" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{isset($_id) ? $_id : ''}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content  card">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật câu hỏi</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">X</button>
            </div>
            <div class="modal-body">
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Nhóm</span>
                    <div class="col-md-8">
                        <select name="parent_id" id="parent_id" class="chzn-select form-control">
                            <option value="" disabled>Chọn nhóm câu hỏi</option>
                            @if(isset($categories))
                            @foreach($categories as $key => $value)
                            <option @if((isset($datas) && $value->id == $datas->parent_id) || (isset($parent_id) && $parent_id == $value->id) ) selected @endif value="{{ $value->id }}">{{ $value->name_category }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Câu hỏi</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->question) ? $datas->question : ''}}" name="question" id="question" placeholder="Nhập câu hỏi..." />
                    </div>
                </div>
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Câu trả lời</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->answer) ? $datas->answer : ''}}" name="answer" id="answer" placeholder="Nhập câu trả lời..." />
                    </div>
                </div>
                {{-- Số thứ tự --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Số thứ tự</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{isset($datas->order) ? $datas->order : $order}}" name="order" id="order" placeholder="Nhập mô tả..." />
                    </div>
                </div>
                {{-- trạng thái --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Trạng thái</span>
                    <div class="col-md-8">
                        <input type="checkbox" name="status" id="status" {{isset($datas->status) && $datas->status == 1 ? 'checked' : ''}} />
                        <span for="is_checkbox_status">Hoạt động</span> <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="btupdate">
                        <button id='btn_update' class="btn btn-primary btn-sm" type="button">
                            Cập nhật
                        </button>
                    </span>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>