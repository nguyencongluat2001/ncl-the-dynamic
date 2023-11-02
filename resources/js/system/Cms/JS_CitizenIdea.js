function JS_CitizenIdea(baseUrl, module, action) {
    // check side bar
    $("#main_citizen-idea").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataDocument = new FormData();
    this.countindex = 0;
    this.countindexCitizenIdea = 0;
}
// Load su kien tren man hinh index
JS_CitizenIdea.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmCitizenIdeaIndex';
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_manager_relate_documents').click(function () {
        myClass.manager_relate_documents(oForm);
    });
    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
    });

    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
}
// Load su kien tren cac minh khac
JS_CitizenIdea.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_CitizenIdea.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmCitizenIdeaIndex';
    var myClass = this;
    var loadding = EFYLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist';
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            loadding.go(100);
            var shtml = myClass.genTable(arrResult['Dataloadlist']['data']);
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
JS_CitizenIdea.prototype.genTable = function (arrResult) {
    var shtml = '';
    var status = 'Đã xem';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == 'MOI_GUI') {
            status = 'Mới gửi'
        } else {
            status = 'Đã xem'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_CITIZEN_IDEA'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_NAME_SENDER'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_ADDRESS_SENDER'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_PHONE_SENDER'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_EMAIL_SENDER'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_SUBJECT'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-citizen-idea').html(shtml);
}
JS_CitizenIdea.prototype.backtoindex = function () {
    $('#modalCitizenIdea').html('');
    $('#frmCitizenIdeaIndex').show();
    $('#modalCitizenIdea').hide();
}
// sua
JS_CitizenIdea.prototype.edit = function (oForm) {
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn góp ý để xem/sửa");
        return false;
    }
    if (i > 1) {
        EFYLib.alertMessage('danger', "Bạn chỉ được chọn một góp ý để xem/sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalCitizenIdea').html(arrResult);
            $('#frmCitizenIdeaIndex').hide();
            $('#modalCitizenIdea').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
        }
    });
}
JS_CitizenIdea.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    myClass.formdata.append('_token', $('#_token').val());
    var data = $(oForm).serialize();
    myClass.formdata.append('data', data);
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: myClass.formdata,
        processData: false,
        contentType: false,
        success: function (arrResult) {
            myClass.formdata = new FormData();
            if (arrResult['success']) {
                $('#addListModal').modal('hide');
                myClass.loadList(oForm);
                $('#modalCitizenIdea').html('');
                $('#frmCitizenIdeaIndex').show();
                $('#modalCitizenIdea').hide();
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
            }
        },
        error: function (arrResult) {
            EFYLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}

JS_CitizenIdea.prototype.delete = function (oForm) {
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn mục cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}