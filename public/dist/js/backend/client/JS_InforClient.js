function JS_InforClient(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    // NclLib.menuActive('.link-privileges');
    NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_InforClient.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmLoadlist_infor';
    $(oForm).find('#btn_changePass').click(function () {
        console.log(12);
        myClass.changePass(oForm);
    })
    
    $('form#frmChangePass').find('#btn_getFormOTP').click(function () {
        myClass.sendOTPMAIL(oForm);
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
    myClass.loadList(oForm);
    // $(oForm).find('#txt_search').click(function () {
    //     /* ENTER PRESSED*/
    //         var page = $(oForm).find('#limit').val();
    //         var perPage = $(oForm).find('#cbo_nuber_record_page').val();
    //         myClass.loadList(oForm, page, perPage);
    //         // return false;
        
    // });
}
// /**
//  * Load màn hình danh sách
//  *
//  * @param oForm (tên form)
//  *
//  * @return void
//  */
JS_InforClient.prototype.loadList = function (oForm) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = $(oForm).serialize();
    console.log(data)
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $("#table-container-library").html(arrResult);
        }
    });
}
JS_InforClient.prototype.loadevent = function (oForm) {
    var myClass = this;
    
    $('form#frmChangePass').find('#btn_getFormOTP').click(function () {
        myClass.sendOTPMAIL(oForm);
    })
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $('form#frmAdd').find('#btn_changePass').click(function () {
        myClass.changePass('form#frmAdd');
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
   
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_InforClient.prototype.changePass = function (oForm) {
    NclLib.loadding();
    var url = this.urlPath + '/changePass';
    var myClass = this;
    var data = $(oForm).serialize();

    // var data = 'id=' + $("#id").val();
    // data += '&email=' +$("#email").val();
    console.log(data)
    // var loadding = NclLib.successLoadding();
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
JS_InforClient.prototype.updatePass = function (oFormCreate) {
    NclLib.loadding();
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
// check otp
JS_InforClient.prototype.sendOTPMAIL = function (oFormCreate) {
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
                  var nameMessage = arrResult['message'];
                  var icon = 'success';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
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
 * Cập nhật thông tin cá nhân
 */
JS_InforClient.prototype.updateCustomer = function(){
    var myClass = this;
    var url = myClass.urlPath + '/updateCustomer';
    var data = $("#frmLoadlist_infor").serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            if(arrResult['success'] == true){
                NclLib.alerMesage(arrResult['message'], 'success', '#1bba00');
            }else{
                NclLib.alerMesage(arrResult['message'], 'danger', '#bd2130');
            }
        }, error: function(e){
            console.log(e);
        }
    });
}