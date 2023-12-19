function JS_BackupData(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-backupdata');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_BackupData.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmBackupData_index';
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    myClass.loadList(oForm);

    $(oForm).find('#btn_sql').click(function () {
        myClass.exportSQL();
    });
    $(oForm).find('#btn_excel').click(function () {
        myClass.exportEXCEL();
    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_BackupData.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = 'search=' + $("#search").val();
    data += '&category=' +$("#category").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
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
            $(oForm).find('#cbo_nuber_record_page').val(perPage);
            var loadding = NclLib.successLoadding();
            myClass.loadevent(oForm);
        }
    });
}
/**
 * Xuất dữ liệu sql
 */
JS_BackupData.prototype.exportSQL = function(table_name = ''){
    var myClass = this;
    var url = myClass.urlPath + '/exportSQL';
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
    if(listitem == '' && table_name == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Phải chọn một đối tượng để xử lý');
        return false;
    }
    var data = {
        _token: $("form#frmBackupData_index #_token").val(),
        table_name: listitem != '' ? listitem : table_name,
    };
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            myClass.downloadFile(arrResult);
        }, error: function(e){
            console.log(e);
        }
    });
}
/**
 * Xuất dữ liệu EXCEL
 */
JS_BackupData.prototype.exportEXCEL = function(table_name = ''){
    var myClass = this;
    var url = myClass.urlPath + '/exportEXCEL';
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
    if(listitem == '' && table_name == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Phải chọn một đối tượng để xử lý');
        return false;
    }
    var data = {
        _token: $("form#frmBackupData_index #_token").val(),
        table_name: listitem != '' ? listitem : table_name,
    };
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            if(arrResult){
                window.open(arrResult);
            }else{
                NclLib.alertMessageBackend('danger', 'Lỗi', 'Xuất dữ liệu không thành công');
            }
        }, error: function(e){
            console.log(e);
        }
    });
}
JS_BackupData.prototype.downloadFile = function(filePath){
    var link=document.createElement('a');
    link.href = filePath;
    link.download = filePath.substr(filePath.lastIndexOf('/') + 1);
    link.click();
}