<form id="frmAddCategory" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{ isset($_id) ? $_id : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content  card">
            <div class="modal-header">
                <h5 class="modal-title">Xem giấy khám sức khỏe</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                    X
                </button>
            </div>
            <div class="modal-body">
                <!-- Họ và tên -->
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Họ và tên</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ isset($datas->name) ? $datas->name : '' }}"
                            name="name" id="name" readonly />
                    </div>
                </div>
                {{--  Email --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Email</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->email) ? $datas->email : '' }}" name="email" id="email"
                            readonly />
                    </div>
                </div>
                {{-- Điện thoại --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label required">Điện thoại</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->phone) ? $datas->phone : '' }}" name="phone" id="phone"
                            readonly />
                    </div>
                </div>
                {{--  Ngày sinh --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Ngày sinh</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->date_of_birth) ? $datas->date_of_birth : '' }}" name="date_of_birth"
                            id="date_of_birth" readonly />
                    </div>
                </div>
                {{--  Giới tính --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Giới tính</span>
                    <div class="col-md-8">
                        <div class="row px-5 mb-2">
                            <div class="d-flex">
                                <div class="form-check" style="margin-right: 20px;">
                                    <input class="form-check-input" type="radio" name="sex" id="male"
                                        readonly value="male" {{ $datas->sex == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">
                                        Nam
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" id="female"
                                        readonly value="female" {{ $datas->sex == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">
                                        Nữ
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  Chiều cao --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Chiều cao</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->height) ? $datas->height : '' }}" name="height" id="height"
                            readonly />
                    </div>
                </div>
                {{--  Cân nặng --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Cân nặng</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->weighed) ? $datas->weighed : '' }}" name="weighed" id="weighed"
                            readonly />
                    </div>
                </div>
                {{--  tiểu sử --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Tiểu sử</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->history_of_pathology) ? $datas->history_of_pathology : '' }}"
                            name="history_of_pathology" id="history_of_pathology" readonly />
                    </div>
                </div>
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Nội dung</span>
                    <div class="col-md-8">
                        <input class="form-control" type="text"
                            value="{{ isset($datas->text) ? $datas->text : '' }}" name="text" id="text"
                            readonly />
                    </div>
                </div>
                {{--  Hình ảnh --}}
                <div class="row form-group" id="div_hinhthucgiai">
                    <span class="col-md-3 control-label">Hình ảnh</span>
                    <div class="col-md-8">
                        <img src="{{ url('/file-image-client/giaykham/') }}/{{ $datas->image }}" alt="Image"
                            style="height: 150px;width: 150px;object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
</form>
