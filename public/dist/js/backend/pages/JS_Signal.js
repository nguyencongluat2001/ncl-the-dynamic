function JS_Signal(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-signal');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Signal.prototype.loadIndex = function() {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmSignal_index';
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
    $(oForm).find('#type').change(function() {
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
JS_Signal.prototype.loadevent = function(oForm) {
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
JS_Signal.prototype.add = function(oForm) {
        var url = this.urlPath + '/create';
        var myClass = this;
        $.ajax({
            url: url,
            type: "GET",
            success: function(arrResult) {
                $('#addmodal').html(arrResult);
                $('#addmodal').modal('show');
                $("#status").attr('checked', true);
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
JS_Signal.prototype.store = function(oFormCreate) {
        var url = this.urlPath + '/update';
        var myClass = this;
        var data = $(oFormCreate).serialize();
        var check = myClass.checkValidate();
        if(check == false){
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
JS_Signal.prototype.loadList = function(oForm, numberPage = 1, perPage = 15) {
        var myClass = this;
        var url = this.urlPath + '/loadList';
        var data = '_token=' + $("#_token").val();
        data += '&search=' + $("#search").val();
        data += '&type=' + $("#type").val();
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
JS_Signal.prototype.edit = function(id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmSignal_index #_token').val();
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
            myClass.loadevent('form#frmAdd');

        }
    });
}
// Xoa mot doi tuong
JS_Signal.prototype.delete = function(oForm) {
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
            var nameMessage = 'Bạn chưa chọn danh mục để xóa!';
            NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
            return false;
        }
        var data = $(oForm).serialize();
        // var url = this.urlPath + "/recordtype/" + listitem;
        var url = this.urlPath + '/delete';
        Swal.fire({
            title: 'Bạn có chắc chắn xóa vĩnh viễn danh mục này không?',
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
 * Thêm một dòng mới trên danh sách
 */
JS_Signal.prototype.addrow = function() {
        var numberRow = $("#body_data tr").length;
        var id = broofa();
        var html = '';
        html += '<tr>';
        // checkbox
        html += '<td align="center"><input type="checkbox" name="chk_item_id" value="' + id + '"></td>';
        // stt
        html += '<td align="center">' + (parseInt(numberRow) + 1) + '</td>';
        // Người nhập
        html += '<td></td>';
        // title
        html += '<td class="td_title_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'title\')">';
        html += '<span id="span_title_' + id + '" class="span_title_' + id + '"></span>';
        html += '</td>';
        // type
        html += '<td class="td_type_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'type\')">';
        html += '<span id="span_type_' + id + '" class="span_type_' + id + '"></span>';
        html += '</td>';
        // target
        html += '<td class="td_target_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'target\')">';
        html += '<span id="span_target_' + id + '" class="span_target_' + id + '"></span>';
        html += '</td>';
        // stop_loss
        html += '<td class="td_stop_loss_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'stop_loss\')">';
        html += '<span id="span_stop_loss_' + id + '" class="span_stop_loss_' + id + '"></span>';
        html += '</td>';
        // price_buy
        html += '<td class="td_price_buy_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'price_buy\')">';
        html += '<span id="span_price_buy_' + id + '" class="span_price_buy_' + id + '"></span>';
        html += '</td>';
        // order
        html += '<td class="td_order_' + id + '" onclick="{select_row(this);}" align="center" ondblclick="click2(\'' + id + '\', \'order\')">';
        html += '<span id="span_order_' + id + '" class="span_order_' + id + '">' + (parseInt(numberRow) + 1) + '</span>';
        html += '</td>';
        // status
        html += '<td onclick="{select_row(this);}" align="center">';
        html += '<label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">';
        html += '<input type="checkbox" hidden class="custom-control-input toggle-status" name="status" id="status_' + id + '" data-id="' + id + '" checked>';
        html += '<span class="custom-control-indicator p-0 m-0" onclick="JS_Signal.changeStatusSignal(\'' + id + '\')"></span>';
        html += '</label></td>';
        // edit
        html += '<td align="center"><span class="text-cursor text-warning" onclick="JS_Signal.edit(\'' + id + '\')"><i class="fas fa-edit"></i></span></td>';
        html += '</tr>';
        $("#body_data").append(html);
}
/**
 * Tạo một id mới ngẫu nhiên
 */
function broofa() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0,
            v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
/**
 * Sự kiện khi nhấn 2 lần vào dòng td để sửa
 */
function click2(id, type) {
    $(".td_" + type + "_" + id).removeAttr('ondblclick');
    var text = $("#span_" + type + "_" + id).html();
    $("#" + type + "_" + id).removeAttr('hidden');
    $("#span_" + type + "_" + id).html('<textarea name="' + type + '" id="' + type + '_' + id + '" rows="2">' + text + '</textarea>');
    $("#" + type + "_" + id).focus();
    $("#span_" + type + "_" + id).removeAttr('id');
    $("#" + type + "_" + id).focusout(function() {
        $(".td_" + type + "_" + id).attr('ondblclick', "click2('" + id + "', '" + type + "')");
        $("#" + type + "_" + id).attr('hidden', true);
        $(".span_" + type + "_" + id).attr('id', 'span_' + type + '_' + id);
        $(".span_" + type + "_" + id).html($("#" + type + "_" + id).val());
        if (text != $(".span_" + type + '_' + id).html()) {
            JS_Signal.updateSignal(id, type, $(".span_" + type + '_' + id).html());
        }
    })
}
/**
 * Cập nhật khi ở màn hình hiển thị danh sách
 */
JS_Signal.prototype.updateSignal = function(id, column, value = '') {
        var myClass = this;
        var url = myClass.urlPath + '/updateSignal';
        var data = 'id=' + id;
        data += '&_token=' + $('#frmSignal_index').find('#_token').val();
        if (column == 'title') { data += '&title=' + (column == 'title' ? value : ""); } else if (column == 'type') { data += '&type=' + value; } else if (column == 'target') { data += '&target=' + value; } else if (column == 'stop_loss') { data += '&stop_loss=' + value; } else if (column == 'price_buy') { data += '&price_buy=' + value; } else if (column == 'order') { data += '&order=' + value; }
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function(arrResult) {
                if (arrResult['success'] == true) {
                    NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                    if (column == 'order') {
                        JS_Signal.loadList();
                    }
                } else {
                    NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                    JS_Signal.loadList();
                }
            },
            error: function(e) {
                console.log(e);
                NclLib.successLoadding();
            }
        });
        $("#" + id).prop('readonly');
}
/**
 * Thay đổi trạng thái
 */
JS_Signal.prototype.changeStatusSignal = function(id) {
    var myClass = this;
    var url = myClass.urlPath + '/changeStatusSignal';
    var data = '_token=' + $("#frmSignal_index #_token").val();
    data += '&status=' + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == true) {
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
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
JS_Signal.prototype.search = function(){
    JS_Signal.loadList();
}
/**
 * Check
 */
JS_Signal.prototype.checkValidate = function(){
    if($("#title").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Tiêu đề không được để trống!');
        $("#title").focus();
        return false;
    }
    // if($("#type").val() == ''){
    //     NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Loại tín hiệu không được để trống!');
    //     $("#type").focus();
    //     return false;
    // }
    if($("#target").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Mục tiêu không được để trống!');
        $("#target").focus();
        return false;
    }
    if($("#stop_loss").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Dừng lỗ không được để trống!');
        $("#stop_loss").focus();
        return false;
    }
    if($("#price_buy").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Giá mua không được để trống!');
        $("#price_buy").focus();
        return false;
    }
}