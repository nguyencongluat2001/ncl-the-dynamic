function JS_Home(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-home');
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
JS_Home.prototype.alerMesage = function(nameMessage,icon,color){
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
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Home.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmHome_index';
    var oFormCreate = 'form#frmAdd';
    // myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
     // form load
    //  $(oForm).find('#type_code').change(function () {
    //     var page = $(oForm).find('#limit').val();
    //     var perPage = $(oForm).find('#cbo_nuber_record_page').val();
    //     myClass.loadList(oForm, page, perPage);
    // });
    $(oForm).find('#txt_search').click(function () {
        /* ENTER PRESSED*/
            myClass.loadMoney();
        
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm)
    });
}
JS_Home.prototype.loadevent = function (oForm) {
    var myClass = this;
    // jQuery(document).ready(function ($) {
    //     jQuery('div[data-ace-editor-id]').each(function () {
    //         new PHPEditor(this);
    //     });
    // });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
}
/**
 * Load màn hình danh sách huyện
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Home.prototype.loadMoney = function () {
    var myClass = this;
    var url = this.urlPath + '/loadMoney';
    oForm = 'form#frmHome_index';
    if ($("#search").val() == '') {
        var nameMessage = 'Vui lòng nhập mã nhân viên!';
        var icon = 'warning';
        var color = '#ffd200';
        var background = 'rgb(33 41 68)';
        NclLib.alerMesageClient(nameMessage,icon,color,background);
        return false;
    }
    var data = $(oForm).serialize();
    NclLib.loadding();
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            var html = `&nbsp; Tổng doanh thu: <span style="font-weight: 600;color: #ff6400;">`+ arrResult.total +` </span><span style="font-size:10px">VND </span>`
            var html_money = '<ul class="list-group">'
            console.log(arrResult.datas.dataMoney)
                   $(arrResult.datas.dataMoney).each(function(index,el) {
                        html_money += `<li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">`
                            html_money += `<div class="d-flex align-items-center text-sm">`
                                html_money += `<span style="color: #181b3b;font-weight: 600;"> &nbsp;&nbsp;`+ el +` </span> &nbsp;&nbsp;<span style="font-size:10px">VND </span>`
                            html_money += `</div>`
                        html_money += `</li>`
                    });
                 html_money += `</ul>`

            $("#iss").html(html);
            $("#iss_money").html(html_money);
        }
    });
}