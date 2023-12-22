<form id="frmAddDepartment" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input class="form-control" name="parent_id" type="hidden" value="{{ $parent_id }}">
    <input class="form-control" name="id" type="hidden" value="{{ $id }}">

    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CẬP NHẬT THÔNG TIN ĐƠN VỊ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="row">
                            <span class="radio col-md-2 text-right">Thuộc đơn vị</span>
                            <div class="col-md-10">
                                <input disabled class="form-control input-md" type="text"
                                    value="{!! $unitparent !!}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Cấp</span>
                            <div class="col-md-8">
                                <select class="form-control" id="group_unit" name="group_unit">
                                    <option value="">-- Chọn cấp --</option>
                                    <option @if ($data['type_group'] == 'SO_NGANH') selected @endif value="SO_NGANH">
                                        Sở Ngành
                                    </option>
                                    <option @if ($data['type_group'] == 'QUAN_HUYEN') selected @endif value="QUAN_HUYEN">
                                        Quận Huyện
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Đơn vị triển khai</span>
                            <div class="col-md-8">
                                <label>
                                    <input {!! $check_dvtk !!} type="checkbox" id="check_dvtk" name="check_dvtk">
                                    Đơn vị là đơn vị triển khai
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Mã đơn vị</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="code" name="code"
                                    value="{!! $data['code'] !!}" xml_data="false" column_name="code">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right control-label required">Tên đơn vị</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="name" name="name"
                                    value="{!! $data['name'] !!}" xml_data="false" column_name="name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Địa chỉ</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="address" name="address"
                                    value="{!! $data['address'] !!}" xml_data="false" column_name="address">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Số điện thoại</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="mobile" name="mobile"
                                    value="{!! $data['mobile'] !!}" xml_data="false" column_name="mobile">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Fax</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="fax" name="fax"
                                    value="{!! $data['fax'] !!}" xml_data="false" column_name="fax">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Email</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="email" name="email"
                                    value="{!! $data['email'] !!}" xml_data="false" column_name="email">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Thứ tự hiển thị</span>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="order" name="order"
                                    value="{!! $data['order'] !!}" xml_data="false" column_name="order"
                                    style="width:20%">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="row">
                            <span class="radio col-md-4 text-right">Trạng thái</span>
                            <div class="col-md-8">
                                <label>
                                    <input type="checkbox" @if ($data['status'] == 1) checked="" @endif
                                        id="status" name="status">
                                    Hoạt động
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button">
                    {{ Lang::get('System::Common.submit') }}
                </button>
                <button type="input" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                    {{ Lang::get('System::Common.close') }}
                </button>
            </div>
        </div>
    </div>
</form>
