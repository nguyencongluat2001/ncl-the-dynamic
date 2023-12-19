function JS_Specialty(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.menuActive('.link-specialty');
    NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
JS_Specialty.prototype.alerMesage = function(nameMessage,icon,color){
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
JS_Specialty.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmSpecialty';
    NclLib.menuActive('.link-specialty');
    $('.chzn-select').chosen({ height: '100%', width: '100%' });

    myClass.loadList(oForm);
    $(oForm).find('#txt_search').click(function () {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
        // return false;
    
    });
}
JS_Specialty.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Specialty.prototype.loadList = function (oForm) {
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
 * Load màn hình danh sách bệnh viện
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Specialty.prototype.getHospital = function (codeKhoa,codeBv) {
    var html = `<a href="/schedule/`+ codeKhoa +`,`+codeBv+`"  class="btn rounded-pill btn-success text-light px-4 light-300">Đặt lịch khám</a>`
    $("#hospital").html(html);
}
/**
 * Cảnh báo chưa chọn bệnh viện
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Specialty.prototype.warning = function () {
    var nameMessage = 'Chưa chọn bệnh viện!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
    return false;
}