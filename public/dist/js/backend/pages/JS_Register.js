function JS_Register(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module + '/' + controller;
    this.email;
    NclLib.loadding();

}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Register.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmRegister';
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Register.prototype.getFonmPhone = function (oForm) {
    var oForm = 'form#frmRegister';
    var url = this.urlPath + '/view_OTP';
    var myClass = this;
    var data = 'phoneChange=' + $('#frmRegister #phone').val();
    $("#show_Otp").removeClass("hidden");
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Register.prototype.getOtp = function (oForm) {
    var oForm = 'form#frmRegister';
    var url = this.urlPath + '/sent_OTP';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 's',
                    title: arrResult.message,
                    showConfirmButton: false,
                    background:'rgb(255 238 67 / 87%)',
                    timer: 5000
                  })
          } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: arrResult.message,
                showConfirmButton: false,
                timer: 5000
              })
          }

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
JS_Register.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/checkLogin';
    console.log(url)

    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#email").val() == '') {
        var nameMessage = 'Email không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        this.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password").val() == '') {
        var nameMessage = 'Mật khẩu không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        this.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                Swal.fire({
                    position: 'top-start',
                    icon: 'success',
                    title: 'Cập nhật thành công',
                    showConfirmButton: false,
                    timer: 3000
                  })
               $('#editmodal').modal('hide');
               myClass.loadList(oFormCreate);

            } else {
                Swal.fire({
                    position: 'top-start',
                    icon: 'error',
                    title: 'Cập nhật thất bại',
                    showConfirmButton: false,
                    timer: 3000
                  })
            }
        }
    });
}
/**
 * lấy thông tin nhân viên giới thiệu
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Register.prototype.getUser = function (oForm) {
    var oForm = 'form#frmRegister';
    var url = this.urlPath + '/getUser';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                var html = '<div class="" id="iss"><label for="">Tên nhân viên</label><input style="color:red" disabled placeholder="'+ arrResult['data']['name'] + '"  type="text" class="form-control"  value="'+ arrResult['data']['name'] + '"></div>'
                $("#iss").html(html);
                Swal.fire({
                    position: 'top-end',
                    icon : 'success',
                    title: arrResult.message,
                    showConfirmButton: false,
                    // background:'#06ff00',
                    timer: 3000
                  })
          } else if (arrResult['success'] == false) {
            Swal.fire({
                position: 'top-end',
                icon : 'warning',
                title: arrResult.message,
                showConfirmButton: false,
                timer: 3000
              })
          }

        }
    });
}
/**
 * Chuyển tab 1
 */
JS_Register.prototype.Tab1 = function(){
    var oForm = '#frmRegister';
    NclLib.loadding();
    $(oForm).find("#tab1-register").show();
    $(oForm).find("#tab2-register").hide();
    $(oForm).find("#tab3-register").hide();
    $(oForm).find("#tab4-register").hide();
    $(".step2").removeClass('active');
}
/**
 * Chuyển tab 2
 */
JS_Register.prototype.Tab2 = function(){
    var oForm = '#frmRegister';
    var myClass = this;
    var email = $(oForm).find("#email").val();
    var regexEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
    var regexPhone = /(84|0[3|5|7|8|9])+([0-9]{8})/;
    if($(oForm).find("#name").val() == ''){
        NclLib.alerMesage('Tên không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#name").focus();
        return false;
    }
    if($(oForm).find("#dateBirth").val() == ''){
        NclLib.alerMesage('Ngày sinh không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#dateBirth").focus();
        return false;
    }
    if(email == ''){
        NclLib.alerMesage('Email không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#email").focus();
        return false;
    }
    if(!email.match(regexEmail)){
        NclLib.alerMesage('Email không đúng định dạng!', 'warning', '#f5ae67');
        $(oForm).find("#email").focus();
        return false;
    }
    if($(oForm).find("#phone").val() == ''){
        NclLib.alerMesage('Số điện thoại không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#phone").focus();
        return false;
    }
    if(!$(oForm).find("#phone").val().match(regexPhone)){
        NclLib.alerMesage('Số điện thoại không đúng định dạng!', 'warning', '#f5ae67');
        $(oForm).find("#phone").focus();
        return false;
    }
    NclLib.loadding();
    if($(oForm).find("#tab2-register").html() == '' || email != this.email){
        var url = myClass.baseUrl + '/register/tab2';
        $.ajax({
            url: url,
            type: "GET",
            data: $(oForm).serialize(),
            success: function(arrResult){
                if(arrResult['success'] == false){
                    NclLib.alerMesage(arrResult['message'], 'warning', '#f5ae67');
                    return false;
                }
                myClass.checkEmail(email);
                $(oForm).find("#tab2-register").html(arrResult);
                $(oForm).find("#tab2-register").show();
                $(".step2").addClass('active');
                $(".step3").removeClass('active');
                $(oForm).find("#tab1-register").hide();
                $(oForm).find("#tab3-register").hide();
                $(oForm).find("#tab4-register").hide();
            }
        });
    }else{
        $(oForm).find("#tab2-register").show();
        $(".step2").addClass('active');
        $(".step3").removeClass('active');
        $(oForm).find("#tab1-register").hide();
        $(oForm).find("#tab3-register").hide();
        $(oForm).find("#tab4-register").hide();
    }
}
/**
 * Chuyển tab 3
 */
JS_Register.prototype.Tab3 = function(){
    var oForm = '#frmRegister';
    var myClass = this;
    if($(oForm).find("#password").val() == ''){
        NclLib.alerMesage('Mật khẩu không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#password").focus();
        return false;
    }
    if($(oForm).find("#repass").val() == ''){
        NclLib.alerMesage('Nhập lại mật khẩu không được để trống!', 'warning', '#f5ae67');
        $(oForm).find("#repass").focus();
        return false;
    }
    if($(oForm).find("#repass").val() != $(oForm).find("#password").val()){
        NclLib.alerMesage('Xác nhận mật khẩu không khớp!', 'warning', '#f5ae67');
        $(oForm).find("#repass").focus();
        return false;
    }
    var url = myClass.baseUrl + '/register/tab3';
    var data = $(oForm).serialize();
    NclLib.loadding();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult){
            $(oForm).find("#tab3-register").html(arrResult);
            $(".step3").addClass('active');
            $(oForm).find("#tab3-register").show();
            $(oForm).find("#tab1-register").hide();
            $(oForm).find("#tab2-register").hide();
            $(oForm).find("#tab4-register").hide();
        }
    });
}
/**
 * Chuyển tab 4
 */
JS_Register.prototype.Tab4 = function(){
    var oForm = '#frmRegister';
    var myClass = this;
    var url = myClass.baseUrl + '/register/tab4';
    var data = $(oForm).serialize();
    NclLib.loadding();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult){
            if(arrResult['success'] == false){
                NclLib.alerMesage(arrResult['message'], 'warning', '#f5ae67');
                return false;
            }
            $(oForm).find("#tab4-register").html(arrResult);
            $(".step4").addClass('active');
            $(oForm).find("#tab4-register").show();
            $(oForm).find("#tab1-register").hide();
            $(oForm).find("#tab2-register").hide();
            $(oForm).find("#tab3-register").hide();
        }, error: function(e){
            console.log(e)
        }
    });
}
/**
 * Thông tin người giới thiệu
 */
JS_Register.prototype.getPersonnel = function () {
    var oForm = '#frmRegister';
    var myClass = this;
    var url = myClass.urlPath + '/getUser';
    var myClass = this;
    var data = '_token=' + $(oForm).find("#_token").val();
    data += '&code_introduce=' + $(oForm).find("#code_introduce").val();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                $("#name_personnel").val(arrResult['data']['name']);
                
          } else if (arrResult['success'] == false) {
            Swal.fire({
                position: 'top-end',
                icon : 'warning',
                title: arrResult.message,
                showConfirmButton: false,
                timer: 3000
              })
          }

        }
    });
}
/**
 * Check email
 */
JS_Register.prototype.checkEmail = function(email = ''){
    this.email = email;
}
