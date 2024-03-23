<form id="frmAddCategory" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="{{ isset($_id) ? $_id : '' }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content  card">
            <div class="modal-header">
                <h5 class="modal-title">Xem bằng cấp</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                    X
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Họ và tên -->
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Họ và tên</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->name) ? $datas->name : '' }}" name="name" id="name"
                                    readonly />
                            </div>
                        </div>
                        {{--  Email --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Email</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->email) ? $datas->email : '' }}" name="email"
                                    id="email" readonly />
                            </div>
                        </div>
                        {{-- Điện thoại --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Điện thoại</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->phone) ? $datas->phone : '' }}" name="phone"
                                    id="phone" readonly />
                            </div>
                        </div>
                        {{--  Ngày sinh --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Ngày sinh</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->date_of_birth) ? $datas->date_of_birth : '' }}"
                                    name="date_of_birth" id="date_of_birth" readonly />
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
                        {{--  Địa chỉ --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Địa chỉ</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->address) ? $datas->address : '' }}" name="address"
                                    id="address" readonly />
                            </div>
                        </div>
                        {{--  Hình ảnh --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Hình ảnh</span>
                            <div class="col-md-8">
                                <img src="{{ url('/file-image-client/bangcap/') }}/{{ $datas->image }}" alt="Image"
                                    style="height: 150px;width: 150px;object-fit: cover;">
                            </div>
                        </div>
                        {{--  Hình ảnh --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Hình ảnh chuyển khoản</span>
                            <div class="col-md-8">
                                <img src="{{ url('/file-image-client/bangcap/') }}/{{ $datas->image_transfer }}"
                                    alt="Image" style="height: 150px;width: 150px;object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- Trường học -->
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Trường học</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->school) ? $datas->school : '' }}" name="school"
                                    id="school" readonly />
                            </div>
                        </div>
                        {{--  Ngành --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Ngành</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->industry) ? $datas->industry : '' }}" name="industry"
                                    id="industry" readonly />
                            </div>
                        </div>
                        {{-- Thời gian ngành --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label required">Tốt nghiệp năm</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->graduate_time) ? $datas->graduate_time : '' }}"
                                    name="phone" id="phone" readonly />
                            </div>
                        </div>
                        {{--  Cấp học --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Xếp loại</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->level) ? $datas->level : '' }}" name="date_of_birth"
                                    id="date_of_birth" readonly />
                            </div>
                        </div>
                        {{--  Địa chỉ cư trú --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Hộ khẩu cư trú</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->permanent_residence) ? $datas->permanent_residence : '' }}"
                                    name="phone" id="phone" readonly />
                            </div>
                        </div>
                        {{--  Căn cước --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Căn cước</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->identity) ? $datas->identity : '' }}" name="identity"
                                    id="identity" readonly />
                            </div>
                        </div>
                        {{--  Ngày cấp căn cước --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Ngày cấp căn cước</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->identity_time) ? $datas->identity_time : '' }}"
                                    name="identity_time" id="identity_time" readonly />
                            </div>
                        </div>
                        {{--  Nơi cấp căn cước --}}
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Nơi cấp căn cước</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->identity_address) ? $datas->identity_address : '' }}"
                                    name="identity_address" id="identity_address" readonly />
                            </div>
                        </div>
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="col-md-3 control-label">Nội dung</span>
                            <div class="col-md-8">
                                <input class="form-control" type="text"
                                    value="{{ isset($datas->text) ? $datas->text : '' }}" name="text"
                                    id="text" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
</form>
