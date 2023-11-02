@php
    use Modules\Core\Efy\FunctionHelper;
@endphp

<form id="frmAddUser" role="form" action="" method="POST">
    @csrf
    <input class="form-control" name="id" id="id" type="hidden" value="{{ $id }}">
    <input class="form-control" name="_token" id="_token" type="hidden" value="{{ csrf_token() }}">
    <div class="modal-dialog modal-xl" style="max-height: 70vh;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CẬP NHẬT THÔNG TIN NGƯỜI DÙNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right control-label required">Tên đăng nhập</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="username" name="username"
                                    value="{{ $data->username ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right control-label required">Họ và tên</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="name" name="name"
                                    value="{{ $data->name ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Mật khẩu</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="password" id="password" name="password"
                                    value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Xác nhận MK</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="password" id="repassword" name="repassword"
                                    value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right control-label">Ngày sinh</label>
                            <div class="col-md-8">
                                <input class="form-control input-md datepicker" type="text" id="birthday" name="birthday" autocomplete="off"
                                    value="{{ date('d/m/Y', strtotime($data->birthday)) ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right control-label">Giới tính</label>
                            <div class="col-md-8">
                                <div class="form-control" style="border: none;">
                                    <label><input type="radio" name="sex"
                                        {{ $data->sex === 1 ? 'checked' : '' }} value="1"> Nam</label>
                                    <label><input class="ms-3" type="radio" name="sex"
                                        {{ $data->sex === 0 ? 'checked' : '' }} value="0"> Nữ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Số điện thoại</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="mobile"
                                    name="mobile" value="{{ $data->mobile ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Email</label>
                            <div class="col-md-8">
                                <input class="form-control input-md" type="text" id="email" name="email"
                                    value="{{ $data->email ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-2 text-right control-label required"
                                style="margin-right:10px;">Vai trò</label>
                            @if ($_SESSION['role'] == 'ADMIN_SYSTEM')
                                <div class="col-md-2">
                                    <label class="radio ms-0">
                                        <input name="role" {{ isset($data->role) && $data->role == 'ADMIN_SYSTEM' ? 'checked' : '' }}
                                            type="radio" value="ADMIN_SYSTEM">
                                        Quản trị hệ thống
                                    </label>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label class="radio ms-0">
                                    <input name="role" {{ isset($data->role) && $data->role == 'ADMIN_OWNER' ? 'checked' : '' }}
                                        type="radio" value="ADMIN_OWNER">
                                    Quản trị tin bài
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Số thứ tự</label>
                            <div class="col-md-4">
                                <input class="form-control input-md" type="text" id="order" name="order"
                                    value="{{ $data->order ?? ($order ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" data-form="add">
                        <div class="row">
                            <label align="right" class="radio ms-0 col-md-4 text-right">Trạng thái</label>
                            <div class="col-md-8">
                                <label>
                                    <input type="checkbox" {{ $check ?? '' }} id="status" name="status"> Hoạt động
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_update" class="btn btn-primary btn-flat" type="button">
                    {{ Lang::get('System::Common.submit') }}
                </button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                    {{ Lang::get('System::Common.close') }}
                </button>
            </div>
        </div>
    </div>
</form>

<style>
    .radio-container label.error {
        float: right;
    }
</style>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy'
    });
</script>
