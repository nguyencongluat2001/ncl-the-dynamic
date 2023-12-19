function JS_Login(baseUrl, module, controller) {
    // $("#collapsesupport").attr("class", "collapse show");
    // $("#main_support").attr("class", "active nav-item");
    // $("#child_support").attr("class", "active nav-item");
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    // this.loadding = NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
JS_Login.prototype.alerMesage = function(nameMessage,icon,color){
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
JS_Login.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmLogin';
    var oFormCreate = 'form#frmAdd';
    // myClass.loadList(oForm);
    $('form#frmLogin').find('#btn_login').click(function () {
        myClass.store('form#frmLogin');
    })
}
JS_Login.prototype.loadevent = function (oForm) {
    var myClass = this;
    // jQuery(document).ready(function ($) {
    //     jQuery('div[data-ace-editor-id]').each(function () {
    //         new PHPEditor(this);
    //     });
    // });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $('form#frmVideo').find('#btn_create').click(function () {
        myClass.store('form#frmVideo');
    })
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Login.prototype.add = function (oForm) {
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
JS_Login.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/checkLogin';

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
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Login.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    // var loadding = NclLib.loadding();
    var url = this.urlPath + '/loadList';
    data = 'offset=' + numberPage;
    data += '&limit=' + perPage;
    var data = $(oForm).serialize();
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
            // loadding.go(100);
            myClass.loadevent(oForm);
        }
    });
}