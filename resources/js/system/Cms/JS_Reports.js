function JS_Reports(baseUrl, module, action) {
    // check side bar
//    $("#main_cms").attr("class", "active");
    $("#main_reports").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataArticle = new FormData();
    this.countindex = 0;
    this.countindexReports = 0;
}
// Load su kien tren man hinh index
JS_Reports.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmReportsIndex';
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
    $(oForm).find('#btn_manager_comment').click(function () {
        myClass.manager_comment(oForm);
    });
    $(oForm).find('#btn_export_excel').click(function () {
        myClass.export_excel(oForm);
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
JS_Reports.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_Reports.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmReportsIndex';
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

JS_Reports.prototype.genTable = function (arrResult) {
    var shtml = '';
    var i = 1;
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == 'HOAT_DONG') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' +i++ + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CREATE'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_TITLE'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['CATEGORY_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_AUTHOR'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CREATE_STAFF_NAME'] + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-reports').html(shtml);
}

JS_Reports.prototype.export_excel = function (oForm) {
    var myClass = this;
    var data = $(oForm).serialize();
    var url = myClass.urlPath + '/export_excel';
    var fromdate = $('#fromdate').val();
    var todate = $('#todate').val();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                window.open(arrResult['urlfile']);
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}

