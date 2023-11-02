/**
 * Xử lý Js về người dùng
 *
 * @author Toanph <skype: toanph155>
 */
function Js_User(baseUrl, module, controller) {
    this.objTree = $("#jstree-tree");
    this.module = module;
    this.baseUrl = baseUrl;
    JS_Tree = new JS_Tree(baseUrl, 'system/users', 'zendtree', this.objTree);
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
Js_User.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
    });
    var myClass = this;
    myClass.loadList();
    $("#search-user-unit").click(function () {
        myClass.search();
    });
    $("#btn-add").click(function () {
        myClass.add();
    });
    $("#btn-edit").click(function () {
        myClass.edit();
    });
    $("#btn-delete").click(function () {
        myClass.delete();
    });
    
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
}

/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_User.prototype.loadList = function (currentPage = 1, perPage = 15) {
    var  oForm = '';
    var myClass = this;
    var url = myClass.urlPath + '/loadList';
    var data = {
        _token: $("#_token").val(),
        txt_search: $("#search_text").val(),
        limit: perPage,
        offset: currentPage,
    };
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $("#dataList").html(arrResult['data']);
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult['perPage']);
        }
    });
}
/**
 * Tìm kiếm
 */
Js_User.prototype.search = function () {
    var myClass = this;
    myClass.loadList();
}
/**
 * Thêm mới người dùng
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_User.prototype.add = function () {
    var myClass = this;
    var url = myClass.urlPath + '/create';
    $.ajax({
        url: url,
        type: "GET",
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('#btn_update').click(function () {
                myClass.update();
            });
        }
    });
}
/**
 * Sửa mới người dùng
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_User.prototype.edit = function () {
    var myClass = this;
    var url = myClass.urlPath + '/edit';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
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
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn đối tượng cần sửa");
        return false;
    }
    if (i > 1) {
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chỉ được chọn một đối tượng để sửa");
        return false;
    }
    var data = {
        itemId: listitem
    }
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('#btn_update').click(function () {
                myClass.update();
            });
        }
    });
}
// Cap nhat nguoi dung
Js_User.prototype.update = function () {
    var oForm = 'form#frmAddUser';
    var myClass = this;
    var url = this.urlPath + '/update';
    if ($(oForm).valid()) {
        // check
        var idunser = $(oForm).find("input[name=id]").val();
        var pass = $(oForm).find('#password').val();
        var passcheck = $(oForm).find('#repassword').val();
        if (idunser !== '') {
            // sua
            if (pass != '' && pass != passcheck) {
                $("#password").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                $("#repassword").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                return false;
            }
        } else {
            console.log();
            $("div[data-form=add]").each((key, value) => {
                if($(value).find('label').hasClass('required') && $(value).find('input').val() == ''){
                    $(value).find('input').after('<label generated="true" class="error">' + $(value).find('label').html() + ' không được để trống</label>');
                    return false;
                }
            })
            if (pass != passcheck) {
                $("#password").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                $("#repassword").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                return false;
            }
        }
        var data = $(oForm).serialize();
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (arrResult) {
                if (arrResult['success']) {
                    $('#addmodal').modal('hide');
                    var page = $("#frmUser_index").find('#_currentPage').val();
                    var perPage = $("#frmUser_index").find('#cbo_nuber_record_page').val();
                    myClass.loadList(page, perPage);
                } else {
                    EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
                    $("#message-infor").addClass('text-white');
                }
            }
        });
    }
}
// Xoa mot doi tuong
Js_User.prototype.delete = function () {
    var myClass = this;
    var check = false;
    var url = this.urlPath + '/delete';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
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
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn đối tượng cần xóa");
        return false;
    }
    var data = {
        itemId: listitem
    }
    $.confirm({
        title: 'Thông báo!',
        content: 'Bạn có chắc chắn muốn xóa những đối tượng này không?',
        autoClose: 'Close|10000',
        buttons: {
            deleteUser: {
                btnClass: 'btn btn-primary',
                text: 'Xóa',
                action: function () {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: data,
                        success: function (arrResult) {
                            if (arrResult['success']) {
                                EfyLib.alertMessage('success', 'Thành công', arrResult['message'], 6000);
                                var page = $("#frmUser_index").find('#_currentPage').val();
                                var perPage = $("#frmUser_index").find('#cbo_nuber_record_page').val();
                                myClass.loadList(page, perPage);
                            } else {
                                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
                            }
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn btn-light',
                text: 'Đóng',
                action: function () {
                }
            }
        }
    });
}
