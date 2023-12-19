function JS_UserLog(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-userLog');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_UserLog.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmUserLog_index';
    myClass.loadList();

    $(oForm).find('#txt_search').click(function () {
        /* ENTER PRESSED*/
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(page, perPage);
        // return false;

    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_UserLog.prototype.loadList = function (numberPage = 1, perPage = 15) {
    var oForm = '#frmUserLog_index';
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = '_token=' + $("#_token").val();
    data += '&search=' + $("#search").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPages = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPages);
            });
            $(oForm).find('#cbo_nuber_record_page').val(perPage);
            var loadding = NclLib.successLoadding();
        }
    });
}
/**
 * Tìm kiếm
 */
JS_UserLog.prototype.search = function () {
    JS_UserLog.loadList();
}
/**
 * View
 */
JS_UserLog.prototype.view = function (user_id) {
    var myClass = this;
    var url = myClass.urlPath + '/view';
    var data = 'user_id=' + user_id;
    $.ajax({
        url: url,
        data: data,
        type: 'GET',
        success: function (arrResult) {
            NclLib.successLoadding();
            $("#addmodal").html(arrResult);
            $("#addmodal").modal('show');
        }, error: function (e) {
            NclLib.successLoadding();
            console.log(e);
        }
    });
}