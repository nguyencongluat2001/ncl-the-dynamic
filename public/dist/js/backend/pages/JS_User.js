function JS_User(baseUrl, module, controller) {
    // $("#collapsesupport").attr("class", "collapse show");
    // $("#main_support").attr("class", "active nav-item");
    // $("#child_support").attr("class", "active nav-item");
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
JS_User.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmUsers_index';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $('form#frmChangePass').find('#btn_changePass').click(function () {
        myClass.changePass(oForm);
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
     // form load
     $(oForm).find('#role').change(function () {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    $(oForm).find('#txt_search').click(function () {
            var page = $(oForm).find('#limit').val();
            var perPage = $(oForm).find('#cbo_nuber_record_page').val();
            myClass.loadList(oForm, page, perPage);
            // return false;
        
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm)
    });
}
JS_User.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
   
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_User.prototype.add = function (oForm) {
    console.log(12)
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
            $("#btn_changePass").hide();
            $('#btn_create').click(function () {
                myClass.store('form#frmAdd');
            })
            // myClass.loadevent(oForm);
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
JS_User.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/create';
    var myClass = this;
    var formdata = new FormData();
    var check = myClass.checkValidate();
    if(check == false){
        return false;
    }
    formdata.append('_token', $("#_token").val());
    formdata.append('id', $("#id").val());
    formdata.append('name', $("#name").val());
    formdata.append('email', $("#email").val());
    formdata.append('dateBirth', $("#dateBirth").val());
    formdata.append('phone', $("#phone").val());
    formdata.append('address', $("#address").val());
    formdata.append('company', $("#company").val());
    formdata.append('position', $("#position").val());
    formdata.append('date_join', $("#date_join").val());
    formdata.append('id_personnel', $("#id_personnel").val());
    formdata.append('role', $("input[name=role]:checked").val());
    if($("#frmAdd #status").is(':checked')){
        formdata.append('status', 1);
    }
    $('form#frmAdd input[type=file]').each(function () {
        var count = $(this)[0].files.length;
        for (var i = 0; i < count; i++) {
            formdata.append('file-attack-' + i, $(this)[0].files[i]);
        }
    });
    console.log(formdata)
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
                  NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                  $('#editmodal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                NclLib.successLoadding();
                NclLib.alerMesage('danger', 'Lỗi', 'Cập nhật thất bại');
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
JS_User.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
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
            $(oForm).find('#cbo_nuber_record_page').val(perPage);
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
JS_User.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var listitem = '';
    var i = 0;
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
          var nameMessage = 'Bạn chưa chọn đối tượng!';
          var icon = 'warning';
          var color = '#f5ae67';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if (i > 1) {
          var nameMessage = 'Bạn chỉ được chọn một đối tượng!';
          var icon = 'warning';
          var color = '#f5ae67';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            $('form#frmAdd').find('#btn_changePass').click(function () {
                myClass.changePass('form#frmAdd');
            })
            // myClass.loadevent(oForm);

        }
    });
}
// Xoa mot doi tuong
JS_User.prototype.delete = function (oForm) {
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
          var nameMessage = 'Bạn chưa chọn đối tượng để xóa!';
          var icon = 'warning';
          var color = '#f5ae67';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    var data = $(oForm).serialize();
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn người dùng này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận'
      }).then((result) => {
        if(result.isConfirmed == true){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    listitem: listitem,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                position: 'top-start',
                                icon: 'success',
                                title: 'Xóa thành công',
                                showConfirmButton: false,
                                timer: 3000
                              })
                              myClass.loadList(oForm);
                          }
                    } else {
                        if (result.isConfirmed) {
                            Swal.fire({
                                position: 'top-start',
                                icon: 'error',
                                title: 'Quá trình xóa đã xảy ra lỗi',
                                showConfirmButton: false,
                                timer: 3000
                              })
                          }
                    }
                }
            });
        }
      })
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_User.prototype.changePass = function (oForm) {
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
JS_User.prototype.updatePass = function (oFormCreate) {
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
                  var html = '<div  id="iss"><label for="">Mã OTP </label> <div class="col-md-6 pt-2"><input style="color:red" id="otp" name="otp"  type="text" class="form-control "  value=""></div></div>'
                  $("#iss").html(html);
                  var nameMessage = arrResult['message'];
                  var icon = 'success';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
                  $("#editPassmodal").html('');
                  $("#editPassmodal").modal('hide');
            } else {
                  var nameMessage = arrResult['message'];
                  var icon = 'warning';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
}
/**
 * Thay đổi trạng thái
 */
JS_User.prototype.changeStatus = function(id){
    var myClass = this;
    var url = myClass.urlPath + '/changeStatus';
    var data = '_token=' + $("#frmUsers_index #_token").val();
    data += '&status=' + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            if(arrResult['success'] == true){
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
            }else{
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
            }
            NclLib.successLoadding();
        }, error: function(e){
            console.log(e);
            NclLib.successLoadding();
        }
    });
}
/**
 * Check Validate
 */
JS_User.prototype.checkValidate = function(){
    if ($("#name").val() == '') {
        var nameMessage = 'Tên người dùng không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        $("#name").focus();
        return false;
    }
    if ($("#phone").val() == '') {
        var nameMessage = 'Số điện thoại không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        $("#phone").focus();
        return false;
    }
    if ($("#email").val() == '') {
        var nameMessage = 'Địa chỉ email không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        $("#email").focus();
        return false;
    }
    if ($("#dateBirth").val() == '') {
        var nameMessage = 'Ngày sinh không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        $("#dateBirth").focus();
        return false;
    }
    if ($("#id").val() == ''){
        // if ($("#password").val() == '') {
        //     var nameMessage = 'Mật khẩu không được để trống!';
        //     NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        //     $("#password").focus();
        //     return false;
        // }
        // if ($("#password_retype").val() == '') {
        //     var nameMessage = 'Chưa nhập mật khẩu xác nhận!';
        //     NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        //     $("#password_retype").focus();
        //     return false;
        // }
        // if($("#password").val() != $("#password_retype").val()){
        //     var nameMessage = 'Mật khẩu xác nhận không khớp';
        //     NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        //     $("#email").focus();
        //     return false;
        // }
        // formdata.append('password', $("#password").val());
    }
    if ($('input[name="is_checkbox_role"]:checked').val() == '') {
        var nameMessage = 'Quyền truy cập không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        return false;
    }
}