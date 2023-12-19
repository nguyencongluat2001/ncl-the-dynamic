function JS_User_info(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-user');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_User_info.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmUsersInfo_index';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_checkbox').click(function () {
        myClass.color_view(oForm);
    });
    $(oForm).find('#btn_changePass').click(function () {
        myClass.changePass(oForm);
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
}
JS_User_info.prototype.loadevent = function (oForm) {
    var myClass = this;
    // $('form#frmView').find('#btn_create').click(function () {
    //     myClass.store('form#frmView');
    // })
    // $('form#frmView').find('#btn_create').click(function () {
    //     myClass.color_view('form#frmView');
    // })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
    $('form#frmChangePass').find('#btn_getFormOTP').click(function () {
        myClass.btn_getFormOTP(oForm);
    })
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_User_info.prototype.add = function (oForm) {
    var url = this.urlPath + '/createForm';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_User_info.prototype.color_view = function (user_id) {
    var url = this.urlPath + '/editColorView';
    var myClass = this;
    is_checkbox = ''
    $('input[name="is_checkbox"]:checked').each(function() {
        is_checkbox =  $(this).val();
    });
    var oForm = 'form#frmUsersInfo_index';
    var data = $(oForm).serialize();
    console.log(data)
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        cache: true,

        success: function (arrResult) {
            window.location.reload(true);
        }
    });
}
/**
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_User_info.prototype.store = function (oFormCreate) {
    console.log(123)
    var url = this.urlPath + '/create';
    var myClass = this;
    is_checkbox_role = ''
    $('input[name="is_checkbox_role"]:checked').each(function() {
        is_checkbox_role =  $(this).val();
    });
    if (is_checkbox_role == '') {
        var nameMessage = 'Quyền truy cập không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    var formdata = new FormData();
    formdata.append('_token', $("#_token").val());
    formdata.append('id', $("#id").val());
    formdata.append('name', $("#name").val());
    formdata.append('code', $("#code").val());
    formdata.append('address', $("#address").val());
    formdata.append('phone', $("#phone").val());
    formdata.append('email', $("#email").val());
    formdata.append('dateBirth', $("#dateBirth").val());
    formdata.append('password', $("#password").val());
    formdata.append('is_checkbox_role', is_checkbox_role);
    $('form#frmAdd input[type=file]').each(function () {
        var count = $(this)[0].files.length;
        for (var i = 0; i < count; i++) {
            formdata.append('file-attack-' + i, $(this)[0].files[i]);
        }
    });

    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  var nameMessage = 'Cập nhật thành công!';
                  var icon = 'success';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
                  $('#editmodal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                var loadding = NclLib.successLoadding();
                  var nameMessage = 'Cập nhật thất bại!';
                  var icon = 'error';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_User_info.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/index';
    var data = 'search=' + $("#search").val();
    data += '&role=' +$("#role").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    console.log(data)
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPages = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPages);
            });
            var loadding = NclLib.successLoadding();
            myClass.loadevent(oForm);
        }
    });
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_User_info.prototype.changePass = function (oForm) {
    var url = this.urlPath + '/changePass';
    var myClass = this;
    var data = 'id=' + $("#id").val();
    data += '&email=' +$("#email").val();
    var loadding = NclLib.successLoadding();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#editPassmodal').html(arrResult);
            $('#editPassmodal').modal('show');
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Cập nhật mật khẩu
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_User_info.prototype.updatePass = function (oFormCreate) {
    var url = this.urlPath + '/updatePass';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#password_old").val() == '') {
        var nameMessage = 'Mật khẩu cũ không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password_new").val() == '') {
        var nameMessage = 'Mật khẩu mới không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password_retype_change").val() == '') {
        var nameMessage = 'Chưa nhập lại mật khẩu mới!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                var html = '<div class="col-md-6 pt-2"><div class="form-group"><p for="example-text-input" class="form-control-label">Mã OTP </p><input required class="form-control color" type="text" value="" name="otp" id="otp" /></div></div>'
                $("#iss").html(html);
                var nameMessage = arrResult['message'];
                var icon = 'success';
                var color = '#1bba00';
                NclLib.alerMesage(nameMessage,icon,color);
            }else if(arrResult['success'] == 3){
                var nameMessage = arrResult['message'];
                var icon = 'success';
                var color = '#1bba00';
                NclLib.alerMesage(nameMessage,icon,color);
                $('#editPassmodal').modal('hide');
                  myClass.loadList(oFormCreate);
            } else {
                  var nameMessage = arrResult['message'];
                  var icon = 'warning';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
}
