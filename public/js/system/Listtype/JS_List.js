function Js_List(baseUrl, module, action) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;
}

// Load su kien tren man hinh index
Js_List.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmlist_index';
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#Units').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_export_cache').click(function () {
        myClass.exportCache(oForm);
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm);
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#search_text').keypress(function (e) {
        if (e.which == 13) {
            myClass.loadList(oForm);
        }
    });
}

// Load su kien tren cac minh khac
Js_List.prototype.loadevent = function (oForm) {
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}

// Lay du lieu cho man hinh danh sach
Js_List.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var oForm = 'form#frmlist_index';
    var myClass = this;
    var loadding = EfyLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadList';
    var dirxml = myClass.baseUrl + '/xml/System/list/quan_tri_doi_tuong_danh_muc.xml';
    if (typeof (Tablelist) === 'undefined') {
        Tablelist = new TableXml(dirxml);
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
            var xmlFilePath = arrResult.xmlFilePath;
            if (dirxml != xmlFilePath) {
                Tablelist = new TableXml(xmlFilePath);
            }
            Tablelist.exportTable(arrResult.Dataloadlist.data, 'id', $('div#table-container'), '', oForm);
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
Js_List.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm = $('form#frmAddListType');
            $('#checkall_process_per_group').click();
            $('#status').attr('checked', 'true');
            myClass.loadevent(oForm);
        }
    });
}

// sua
Js_List.prototype.edit = function (oForm) {
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
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm = $('form#frmAddListType');
            myClass.loadevent(oForm);
        }
    });
}

// Them loai danh muc
Js_List.prototype.delete = function (oForm) {
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
                                myClass.loadList(oForm);
                                EfyLib.alertMessage('success', 'Thành công', 'Xóa thành công');
                            } else {
                                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
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

// Xuat caches
Js_List.prototype.exportCache = function (oForm) {
    var url = this.urlPath + '/exportCache';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                EfyLib.alertMessage('success', 'Thành công', arrResult['message']);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
            }
        },
        error: function (arrResult) {
            EfyLib.alertMessage('warning', 'Lỗi', arrResult.responseJSON);
        }
    });
}

// Cap nhat loai danh muc
Js_List.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oForm).serialize();
    var listtype_status = 'KHONG_HOAT_DONG';
    var stringxml = EfyLib._save_xml_tag_and_value_list(oForm);
    var list_status = 'KHONG_HOAT_DONG'
    if ($(oForm).find('#status').is(':checked')) {
        var list_status = 'HOAT_DONG';
    }
    var listownercode = '';
    $('[name="GROUP_OWNERCODE"]:checked').each(function (index) {
        listownercode += $(this).val() + ",";
    });
    data += '&listtype_status=' + listtype_status;
    data += '&stringxml=' + stringxml;
    data += '&list_status=' + list_status;
    data += '&ListOwnercode=' + listownercode;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addListModal').modal('hide');
                var oForm1 = 'form#frmlist_index';
                var page = $(oForm1).find('#_currentPage').val();
                var perPage = $(oForm1).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
                EfyLib.alertMessage('success', 'Thành công', arrResult['message']);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
            }
        }
    });
}