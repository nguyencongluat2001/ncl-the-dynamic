function JS_Schedule(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.menuActive('.link-facilities');
    NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
JS_Schedule.prototype.alerMesage = function(nameMessage,icon,color){
    Swal.fire({
        position: 'top-start',
        icon: icon,
        title: nameMessage,
        color: color,
        showConfirmButton: false,
        width:'30%',
        timer: 2500
      })
}

/**
 * Hàm load màn hình index
 *
 * @return void
 */
JS_Schedule.prototype.loadIndex = function () {
    var myClass = this;
    // $('.datepicker').datepicker();
    var oForm = 'form#frmHospital';
    NclLib.menuActive('.link-facilities');
    $('.chzn-select').chosen({ height: '100%', width: '100%' });

    myClass.loadList(oForm);

    $('form#frmAdd').find('#btn_register').click(function () {
        myClass.add('form#frmAdd');
    })
    $(oForm).find('#txt_search').click(function () {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
        // return false;
    
    });
}
JS_Schedule.prototype.loadevent = function (oForm) {
    var myClass = this;
    // $('form#frmAdd').find('#btn_create').click(function () {
    //     myClass.store('form#frmAdd');
    // })
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.loadList = function (oForm) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            myClass.loadevent(oForm);
        }
    });
}
/**
 * Hàm hiển thị modal thanh toán 
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.add = function (oForm) {
    var url = this.urlPath + '/createForm';
    var myClass = this;
    var oForm = 'form#frmSendSchedule';
    var data = $(oForm).serialize();
    if ($("#code_specialty").val() == '') {
        var nameMessage = 'Chưa chọn khoa khám bệnh!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#name").val() == '') {
        var nameMessage = 'Tên bệnh nhân không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#phone").val() == '') {
        var nameMessage = 'Số điện thoại không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#sex").val() == '') {
        var nameMessage = 'Giới tính không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#date_of_brith").val() == '') {
        var nameMessage = 'Ngày sinh không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#code_tinh").val() == '') {
        var nameMessage = 'Tỉnh thành không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#code_huyen").val() == '') {
        var nameMessage = 'Quận , huyện không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#code_xa").val() == '') {
        var nameMessage = 'Phường , xã không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#address").val() == '') {
        var nameMessage = 'Địa chỉ chi tiết không được để trống!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    if ($("#reason").val() == '') {
        var nameMessage = 'Bạn chưa nêu lý do khám!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            $("#status").attr('checked', true);
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Hàm hiển thị modal thanh toán 
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.getTypeBank = function (type) {
    if(type=='BANK'){
        $('#bank').removeClass("hiddel");
        $('#momo').removeClass("show");
        $('#momo').addClass("hiddel");
        $('#bank').addClass("show");
    }
    else{
        $('#momo').removeClass("hiddel");
        $('#bank').removeClass("show");
        $('#bank').addClass("hiddel");
        $('#momo').addClass("show");
    }
    this.type_bank = type;
}
/**
 * Load màn hình danh sách huyện
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.getHuyen = function (codeTinh) {
    var myClass = this;
    var url = this.urlPath + '/getHuyen';
     var data = '&codeTinh=' + codeTinh;
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            // var html = '<label for="">Quận huyện <span class="request_star">*</span></label>'
            var html = `<select onchange="JS_Schedule.getXa(this.value)" class="form-control input-sm chzn-select" name="code_huyen" id="code_huyen">`
            html += `<option value="">--Chọn quận huyện--</option>`
            $(arrResult.data.huyen).each(function(index,el) {
                 html += `<option value="`+ el.code_huyen +`">`+ el.name +`</option>`
             });
             html += `</select>`
            $("#iss").html(html);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('.chzn-select').trigger('chosen:updated');
        }
    });
}
/**
 * Load màn hình danh sách phường xã
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.getXa = function (codeHuyen) {
    var myClass = this;
    var url = this.urlPath + '/getXa';
     var data = '&codeHuyen=' + codeHuyen;
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            // var html = '<label for="">Phường xã <span class="request_star">*</span></label>'
            var html = `<select class="form-control input-sm chzn-select" name="code_xa" id="code_xa">`
            html += `<option value="">--Chọn phường xã--</option>`
            $(arrResult.data.xa).each(function(index,el) {
                 html += `<option value="`+ el.code_xa +`">`+ el.name +`</option>`
             });
             html += `</select>`
            $("#iss_xa").html(html);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('.chzn-select').trigger('chosen:updated');
        }
    });
}
    /**
 * Load màn hình danh sách phường xã
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.sendPayment = function (data) {
    var myClass = this;
    var url = this.urlPath + '/sendPayment';
    if(this.type_bank == '' || this.type_bank == undefined){
        var nameMessage = 'Chưa chọn hình thức thanh toán!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    var formdata = new FormData();
    formdata.append('_token', $("#_token").val());
    formdata.append('code_hospital', $("#code_hospital").val());
    formdata.append('code_physician', $("#code_physician").val());
    formdata.append('code_specialty', $("#code_specialty").val());
    formdata.append('type_payment', this.type_bank);
    formdata.append('money', $("#money").val());
    formdata.append('money', $("#money").val());
    formdata.append('name', $("#name").val());
    formdata.append('phone', $("#phone").val());
    formdata.append('code_insurance', $("#code_insurance").val());
    formdata.append('sex', $("#sex").val());
    formdata.append('email', $("#email").val());
    formdata.append('date_of_brith', $("#date_of_brith").val());
    formdata.append('code_tinh', $("#code_tinh").val());
    formdata.append('code_huyen', $("#code_huyen").val());
    formdata.append('code_xa', $("#code_xa").val());
    formdata.append('address', $("#address").val());
    formdata.append('date_sampling', $("#date_sampling").val());
    formdata.append('hour_sampling', $("#hour_sampling").val());
    formdata.append('code_introduce', $("#code_introduce").val());
    formdata.append('reason', $("#reason").val());

    $('form#frmAdd input[type=file]').each(function () {
        var count = $(this)[0].files.length;
        if (count > 0) {
            for (var i = 0; i < count; i++) {
                formdata.append('file-attack-' + i, $(this)[0].files[i]);
            }
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
            if (arrResult['status'] == true) {
                var nameMessage = 'Thông báo , Đặt lịch thành công';
                var icon = 'success';
                var color = '#344767';
                NclLib.alerMesage(nameMessage,icon,color);
                $('#editmodal').modal('hide');
                setTimeout(() => {
                    window.location.replace(myClass.baseUrl+'/searchschedule');
                }, 2000)
            } else {
                var nameMessage = 'Đặt lịch thất bại';
                var icon = 'warning';
                var color = '#344767';
                NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
}
/**
 * lấy thông tin nhân viên giới thiệu
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Schedule.prototype.getUser = function (oForm) {
    var myClass = this;
    var oForm = 'form#frmSendSchedule';
    var url = myClass.urlPath + '/getUser';
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                $("#user_introduce_name").val(arrResult['data']['name']);
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
    })
}
/**
* Load số tiền
*
* @param oForm (tên form)
*
* @return void
*/
JS_Schedule.prototype.getMoney = function (codeSpecialty) {
    var myClass = this;
    var url = this.urlPath + '/getMoney';
    var data = '&codeSpecialty=' + codeSpecialty;
    var oForm = 'form#frmSendSchedule';
    data += '&code_hospital=' + $("#code_hospital").val();
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            var htmls = '<span>Số tiền khám: </span>';
            htmls += `<input id="money" type="hidden" class="form-control" name="money" value="`+ arrResult.data.money +`">`
            htmls += `<span><span style="font-size: 25px;font-weight: 500;color: #ff9400;">`+ arrResult.data.moneyConvert +`</span> VND</span>`
            $("#moneys").html(htmls);
        }
    });
}