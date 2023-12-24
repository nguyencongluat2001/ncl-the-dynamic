<form id="frmAdd" role="form" action="" method="POST" enctype="multipart/form-data">
	@csrf
	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="id" id="id" value="{{!empty($data['id'])?$data['id']:''}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content card">
			<div class="modal-header">
				<h5 class="modal-title">Cập nhật người dùng</h5>
				<button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
					X
				</button>
			</div>
			<div class="card-body">
				<p class="text-uppercase text-sm">Thông tin cơ bản</p>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label required">Tên</p>
							<input class="form-control" type="text" value="{{!empty($data['name'])?$data['name']:''}}" name="name" id="name" placeholder="Nhập tên người dùng..." />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label required">Địa chỉ Email</p>
							<input class="form-control" type="email" value="{{!empty($data['email'])?$data['email']:''}}" name="email" id="email" placeholder="Nhập email..." />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label required">Ngày sinh</p>
							<input class="form-control" type="date" value="{{!empty($data['dateBirth'])?$data['dateBirth']:''}}" name="dateBirth" id="dateBirth" placeholder="Chọn ngày sinh..." />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label required">Số điện thoại</p>
							<input class="form-control" type="text" value="{{!empty($data['phone'])?$data['phone']:''}}" name="phone" id="phone" placeholder="Nhập số điện thoại..." />
						</div>
					</div>
					@if(!empty($data) && $_SESSION["email"] == $data['email'] || $_SESSION["role"] == 'ADMIN')
					<span>
						<button class="btn btn-primary btn-sm" type="button" id='btn_changePass'>
							Đổi mật khẩu
						</button>
					</span>
					@endif
				</div>
				<hr class="horizontal dark">
				<p class="text-uppercase text-sm">Thông tin liên lạc</p>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Địa chỉ</p>
							<input class="form-control" type="text" value="{{!empty($data['address'])?$data['address']:''}}" name="address" id="address" placeholder="Nhập địa chỉ..." />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">ID nhân sự</p>
							<input class="form-control" type="text" value="{{!empty($data['id_personnel'])?$data['id_personnel']:''}}" name="id_personnel" id="id_personnel">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Công ty</p>
							<input class="form-control" type="text" value="{{!empty($data['company'])?$data['company']:''}}" name="company" id="company">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Chức vụ</p>
							<input class="form-control" type="text" value="{{!empty($data['position'])?$data['position']:''}}" name="position" id="position">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<p for="example-text-input" class="form-control-label">Gia nhập ngày</p>
							<input class="form-control" type="date" value="{{!empty($data['date_join'])?$data['date_join']:''}}" name="date_join" id="date_join">
						</div>
					</div>
				</div>
				{{-- Quyền truy cập --}}
				<div class="row form-group" id="div_hinhthucgiai">
					<span class="col-md-3 control-label required">Quyền truy cập</span>
					<div class="col-md-5">
					     @if ($_SESSION['role'] == 'ADMIN')
						<input type="radio" value="ADMIN" name="role" id="role" {{!empty($data['role']) && $data['role'] == 'ADMIN' ? 'checked' : ''}} />
						<label for="role">Quản trị hệ thống</label> <br>
						
						<input type="radio" value="EMPLOYEE" name="role" id="role" {{!empty($data['role']) && $data['role'] == 'EMPLOYEE' ? 'checked' : ''}} />
						<label for="role">Nhân viên</label><br>
						
						<input type="radio" value="CTV" name="role" id="role" {{!empty($data['role']) && $data['role'] == 'CTV' ? 'checked' : ''}} />
						<label for="role">Cộng tác viên</label><br>
						@endif
					</div>
					<div class="col-md-4">
						<label for="">Trạng thái</label><br>
						<input type="checkbox" id="status" name="status" {{isset($data['status']) && $data['status'] == 1 ? 'checked' : ''}}>
						<label for="status">Hoạt động</label>
					</div>
					<div class="modal-body">
						<div>
							<label>Chọn ảnh đại diện</label><br>
							<label for="avatar" class="label-upload">Chọn ảnh</label>
							<input hidden type="file" name="avatar" id="avatar" onchange="readURL(this)"><br>
							@if(!empty($data['avatar']))
							<img id="show_img" src="{{url('/file-image/avatar/')}}/{{$data['avatar']}}" alt="Image" style="width:150px">
							@else
							<img id="show_img" hidden alt="Image" style="width:150px">
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<span id="btupdate">
							<button onclick="JS_User.store('form#frmAdd')" id='btn_create' class="btn btn-primary btn-sm" type="button">
								Cập nhật
							</button>
						</span>
						<button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
							Đóng
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script src="../assets/js/croppie.js"></script>
<script src="../assets/js/croppie.min.js"></script>
<script>
	$(document).ready(function() {
		$image_crop = $('#image_demo').croppie({
			enableExif: true,
			viewport: {
				width: 200,
				height: 200,
				type: 'circle'
			},

			boundary: {
				width: 300,
				height: 300
			}
		});

		$('#upload_image').on('change', function() {
			var reader = new FileReader();
			reader.onload = function(event) {
				$image_crop.croppie('bind', {
					url: event.target.result
				})
			}
			reader.readAsDataURL(this.files[0]);
			$('#uploadimage').show();
		});

		$('.crop_image').click(function(event) {
			$image_crop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function(response) {
				console.log(1, $image_crop)
			});
		})
	})
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
			$('#show_img').attr('src', e.target.result).width(150);
			};
			$("#show_img").removeAttr('hidden');

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>