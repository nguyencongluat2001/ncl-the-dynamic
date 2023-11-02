/*
 * Creater: Toanph
 * Date:01/12/2016#
 * Idea: Lop xu ly lien quan den loai danh muc#
 */
function Js_Listtype(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

// Load su kien tren man hinh index
Js_Listtype.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmlisttype_index';
    myClass.loadList(oForm);
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#Units').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_export_cache').click(function () {
        myClass.exportcache(oForm);
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm);
    });
    // Tim kiem loai danh muc
    $(oForm).find('#btn_search').click(function () {
        var page = $(oForm).find('#_currentPage').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);

    });
    // Tim kiem
    $(oForm).find('#search_text').keypress(function (e) {
        // if (e.which == 13 && e.shiftKey) {
        //     console.log('Shift enter');
        //     // JS_Search.search_file('content');
        // }
        if (e.which == 13) {
            myClass.loadList(oForm);
        }
    });
    // xuat danh muc ve cac don vi
    $('#btn_pushtounit').click(function () {
        myClass.openModalUnit();
    });
}

// Load su kien tren cac minh khac
Js_Listtype.prototype.loadevent = function (oForm) {
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
    $('#btn_savepushtounit').click(function () {
        var oForm = 'form#frmpushtounit';
        myClass.sendToUnit(oForm);
    });
}

// Lay du lieu cho man hinh danh sach
Js_Listtype.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var loadding = EfyLib.loadding();
    loadding.go(20);
    var myClass = this;
    var url = myClass.urlPath + '/loadList';
    var filexml = $("#_filexml").val();
    var dirxml = myClass.baseUrl + '/xml/System/listtype/' + filexml;
    if (typeof (Tablelisttype) === 'undefined') {
        Tablelisttype = new TableXml(dirxml);
    }
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            Tablelisttype.exportTable(arrResult.Dataloadlist.data, 'id', $('div#table-container'), '', oForm);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}

// Them loai danh muc
Js_Listtype.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addListypeModal').html(arrResult);
            $('#addListypeModal').modal('show');
            var oForm = $('form#frmAddListType');
            myClass.loadevent(oForm);
        }
    });
}

// sua
Js_Listtype.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
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
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn danh mục cần sửa");
        return false;
    }
    if (i > 1) {
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chỉ được chọn một danh mục để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addListypeModal').html(arrResult);
            $('#addListypeModal').modal('show');
            var oForm = $('form#frmAddListType');
            myClass.loadevent(oForm);
        }
    });
}

// Them loai danh muc
Js_Listtype.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn danh mục cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
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
                                myClass.loadList('form#frmlisttype_index');
                                EfyLib.alertMessage('success', 'Thành công', 'Xóa thành công');
                            } else {
                                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
                            }
                        },
                        error: function (arrResult) {
                            EfyLib.alertMessage('warning', 'Lỗi', arrResult.responseJSON);
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

// Cap nhat loai danh muc
Js_Listtype.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    $listownercode = '';
    $('[name="GROUP_OWNERCODE"]:checked').each(function (index) {
        $listownercode += $(this).val() + ",";
    });
    var data = $(oForm).serialize();
    data += '&ListOwnercode=' + $listownercode;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addListypeModal').modal('hide');
                myClass.loadList('form#frmlisttype_index');
                EfyLib.alertMessage('success', 'Thành công', arrResult['message']);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
            }
        },
        error: function (arrResult) {
            EfyLib.alertMessage('warning', 'Lỗi', arrResult.responseJSON, 6000);
        }
    });
}

// Xuat cache loai danh muc
Js_Listtype.prototype.exportcache = function (oForm) {
    var url = this.urlPath + '/exportcache';
    var myClass = this;
    var data = $(oForm).serialize();
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addListypeModal').modal('hide');
                myClass.loadList('form#frmlisttype_index');
                EfyLib.alertMessage('success', 'Thành công', arrResult['message']);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
            }
            EfyLib.successLoadImage();
        },
        error: function (arrResult) {
            EfyLib.alertMessage('warning', 'Lỗi', arrResult.responseJSON);
            EfyLib.successLoadImage();
        }
    });
}

Js_Listtype.prototype.openModalUnit = function () {
    var myClass = this;
    var url = this.urlPath + '/openmodalunit';
    var data = {
        _token: $('#_token').val()
    };
    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function (result) {
            $('#addListypeModal').html(result);
            $('#addListypeModal').modal('show');
            myClass.loadevent();
        }
    })
}

Js_Listtype.prototype.sendToUnit = function (oForm) {
    var url = this.urlPath + '/saveunitlisttype';
    var data = $(oForm).serialize();
    var obj = $('#UNIT_SELECT').find('input[name="UNIT_SELECT"]');
    var listID = '';
    var listunit = '';
    $('input[name="UNIT_SELECT"]:checked').each(function () {
        listunit += ',' + $(this).val();
    });
    $('input[name="UNITDEPART_SELECT"]:checked').each(function () {
        listunit += ',' + $(this).val();
    });
    if (listunit == '') {
        EfyLib.alertMessage('warning', 'Thông báo', 'Chưa chọn đơn vị');
        return false;
    }
    data += '&listID=' + listunit;
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (result) {
            if (result.success) {
                EfyLib.alertMessage('success', 'Thành công', result.message);
                $('#addListypeModal').modal('hide');
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', 'Có lỗi xảy ra!');
            }
            EfyLib.successLoadImage();
        }
    })
}