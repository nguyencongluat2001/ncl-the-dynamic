function JS_AppointmentAtHome(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-appointmentathome');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_AppointmentAtHome.prototype.loadIndex = function() {
    var myClass = this;
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmApproveAthome';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function() {
        myClass.add(oForm);
    });
    $('form#frmAdd').find('#btn_create').click(function() {
        myClass.store('form#frmAdd');
    })
    $(oForm).find('#btn_edit').click(function() {
        myClass.edit(oForm);
    });
    // form load
    $(oForm).find('#type_at_home').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    $(oForm).find('#status').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    // form load
    $(oForm).find('#fromdate').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    // form load
    $(oForm).find('#todate').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    $(oForm).find('#txt_search').click(function() {
        /* ENTER PRESSED*/
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
        // return false;

    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function() {
        myClass.delete(oForm)
    });
}
JS_AppointmentAtHome.prototype.loadevent = function(oForm) {
        var myClass = this;
        $('form#frmAdd').find('#btn_create').click(function() {
            myClass.store('form#frmAdd');
        })
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_AppointmentAtHome.prototype.add = function(oForm) {
    var url = this.urlPath + '/create';
    var myClass = this;
    $.ajax({
        url: url,
        type: "GET",
        success: function(arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $("#status").attr('checked', true);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $("#role_client").change(function(){
                myClass.getUserVIP();
            });
            myClass.loadevent(oForm);
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
JS_AppointmentAtHome.prototype.loadList = function(oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = '_token=' + $("#_token").val();
    data += '&search=' + $("#search").val();
    data += '&type_at_home=' + $("#type_at_home").val();
    data += '&status=' + $("#status").val();
    data += '&fromdate=' + $("#fromdate").val();
    data += '&todate=' + $("#todate").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        // cache: true,
        data: data,
        success: function(arrResult) {
            $("#table-container").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function() {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function() {
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
JS_AppointmentAtHome.prototype.edit = function(id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmAppointmentAtHome_index #_token').val();
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == false) {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $("#role_client").change(function(){
                myClass.getUserVIP();
            });
            myClass.loadevent('form#frmAdd');

        }
    });
}
// Xoa mot doi tuong
JS_AppointmentAtHome.prototype.delete = function(oForm) {
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function() {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        var nameMessage = 'Bạn chưa chọn lịch để xóa!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    var data = $(oForm).serialize();
    // var url = this.urlPath + "/recordtype/" + listitem;
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn lịch này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận'
    }).then((result) => {
        if (result.isConfirmed == true) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    listitem: listitem,
                },
                success: function(arrResult) {
                    if (arrResult['success'] == true) {
                        if (result.isConfirmed) {
                            var nameMessage = 'Xóa thành công!';
                            NclLib.alertMessageBackend('success', 'Thông báo', nameMessage);
                            myClass.loadList(oForm);
                        }
                    } else {
                        if (result.isConfirmed) {
                            var nameMessage = 'Quá trình xóa đã xảy ra lỗi!';
                            NclLib.alertMessageBackend('danger', 'Lỗi', nameMessage);
                        }
                    }
                }
            });
        }
    })
}
/**
 * Thay đổi trạng thái
 */
JS_AppointmentAtHome.prototype.changeStatusAppointmentAtHome = function(id) {
    var myClass = this;
    var oForm = 'form#frmApproveAthome';
    var url = myClass.urlPath + '/changeStatusAppointmentAtHome';
    var data = '_token=' + $("#frmApproveAthome #_token").val();
    data += '&status=' + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == true) {
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                myClass.loadList(oForm);
            } else {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
            }
            NclLib.successLoadding();
        },
        error: function(e) {
            console.log(e);
            NclLib.successLoadding();
        }
    });
}
/**
 * Tìm kiếm
 */
JS_AppointmentAtHome.prototype.search = function(){
    JS_AppointmentAtHome.loadList();
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_AppointmentAtHome.prototype.showDetail = function(id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmAppointmentAtHome_index #_token').val();
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == false) {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $("#role_client").change(function(){
                myClass.getUserVIP();
            });
            myClass.loadevent('form#frmAdd');

        }
    });
}
