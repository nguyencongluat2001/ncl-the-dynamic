<style>
	    .color{
        color:black;
    }
</style>

<div class="modal-dialog modal-lg">
	<div class="modal-content card">
		<form id="frmChangePass">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="user_id" id="user_id" value="{{!empty($data['id'])?$data['id']:''}}">
			<div class="modal-header">
				<h5 class="modal-title">Đổi mật khẩu</h5>
				<!-- <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
					X
				</button> -->
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Email</p>
							<input class="form-control color" type="text" value="{{!empty($data['email_acc'])?$data['email_acc']:''}}" name="email_acc" id="email_acc" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Mật khẩu cũ</p>
							<input class="form-control color" type="password" value="" name="password_old" id="password_old" />
						</div>
					</div>
					<div class="col-md-6 pt-2">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Mật khẩu mới</p>
							<input class="form-control color" type="password" value="" name="password_new" id="password_new" />
						</div>
					</div>
					<div class="col-md-6 pt-2">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Nhập lại mật khẩu</p>
							<input class="form-control color" type="password" value="" name="password_retype_change" id="password_retype_change" />
						</div>
					</div>
				</div>
				<!-- <div class="modal-footer">
					<span id="btn_getFormOTP">
						<button id='btn_getFormOTP' class="btn btn-primary btn-sm" type="button">
							Xác nhận
						</button>
					</span>
					<button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
						Đóng
					</button>
				</div> -->
				<div class="modal-footer">
					<span id="btn_updatePass">
						<button id='btn_updatePass' class="btn btn-primary btn-sm" type="button">
							Cập nhật
						</button>
					</span>
					<button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
						Đóng
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
</div>