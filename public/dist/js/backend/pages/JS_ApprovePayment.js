function JS_ApprovePayment(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-approvepayment');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_ApprovePayment.prototype.loadIndex = function() {
    var myClass = this;
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmApprovePayment_index';
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
    $(oForm).find('#type_payment').change(function() {
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
JS_ApprovePayment.prototype.loadevent = function(oForm) {
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
JS_ApprovePayment.prototype.add = function(oForm) {
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
 * Lấy thông tin khách hàng theo loại VIP
 */
JS_ApprovePayment.prototype.getUserVIP = function() {
    var url = this.urlPath + '/getUserVIP';
    var myClass = this;
    var data = 'role_client=' + $("#role_client").val();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult) {
            $("#user_id").html(arrResult);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('.chzn-select').trigger('chosen:updated');
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
JS_ApprovePayment.prototype.store = function(oFormCreate) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#role_client").val() == '') {
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Loại VIP không được để trống!');
        $("#role_client").focus();
        return false;
    }
    if ($("#user_id").val() == '') {
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Khách hàng không được để trống!');
        $("#user_id").focus();
        return false;
    }
    if ($("#stop_loss").val() == '') {
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Dừng lỗ không được để trống!');
        $("#stop_loss").focus();
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == true) {
                var nameMessage = 'Cập nhật thành công!';
                NclLib.alertMessageBackend('success', 'Thông báo', nameMessage);
                $('#addmodal').modal('hide');
                myClass.loadList(oFormCreate);

            } else {
                var nameMessage = 'Cập nhật thất bại!';
                NclLib.alertMessageBackend('danger', 'Lỗi', nameMessage);
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
JS_ApprovePayment.prototype.loadList = function(oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = '_token=' + $("#_token").val();
    data += '&search=' + $("#search").val();
    data += '&type_payment=' + $("#type_payment").val();
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
JS_ApprovePayment.prototype.edit = function(id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmApprovePayment_index #_token').val();
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
JS_ApprovePayment.prototype.delete = function(oForm) {
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
JS_ApprovePayment.prototype.changeStatusApprovePayment = function(id) {
    var myClass = this;
    var url = myClass.urlPath + '/changeStatusApprovePayment';
    var data = '_token=' + $("#frmApprovePayment_index #_token").val();
    data += '&status=' + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == true) {
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                myClass.loadList();
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
JS_ApprovePayment.prototype.search = function(){
    JS_ApprovePayment.loadList();
}