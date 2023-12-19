function JS_About(baseUrl, module, controller, type = '') {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    if(type != 'home'){
        NclLib.menuActive('.link-about');
    }
    NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_About.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    // form load
    if(myClass.controller == 'about'){
        NclLib.menuActive('.link-index');
        NclLib.menuActive_child('.link-index');
        var oFormBlog = 'form#frmLoadlist_blog';
        myClass.loadListTHTT(oFormBlog);
    }else if(myClass.controller == 'session'){
        NclLib.menuActive('.link-session');
        NclLib.menuActive_child('.link-session');
        var oFormBlog = 'form#frmLoadlist_blog';
        myClass.loadListTKP(oFormBlog);
    }else if(myClass.controller == 'industry'){
        NclLib.menuActive('.link-industry');
        NclLib.menuActive_child('.link-industry');
        var oFormBlog = 'form#frmLoadlist_blog';
        myClass.loadListPTN(oFormBlog);
    }else if(myClass.controller == 'stock'){
        NclLib.menuActive('.link-stock');
        NclLib.menuActive_child('.link-stock');
        var oFormBlog = 'form#frmLoadlist_blog';
        myClass.loadListPTCP(oFormBlog);
    }
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_About.prototype.loadListTHTT = function (oFormBlog,numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = myClass.urlPath + '/loadListTHTT';
    var data = $(oFormBlog).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    data += '&category=BAO_CAO_THTT';
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-blog-container").html(arrResult);
            $(oFormBlog).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListTHTT(oFormBlog, page, perPage);
            });
            $(oFormBlog).find('#cbo_nuber_record_page').change(function () {
                var page = $(oFormBlog).find('#_currentPage').val();
                var perPages = $(oFormBlog).find('#cbo_nuber_record_page').val();
                myClass.loadListTHTT(oFormBlog, page, perPages);
            });
        }
    });
}
/**
 * Danh sách tổng kết phiên
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_About.prototype.loadListTKP = function (oFormBlog,numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = myClass.urlPath + '/loadListTKP';
    var data = $(oFormBlog).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    data += '&category=BAO_CAO_TKP';
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-blog-container").html(arrResult);
            $(oFormBlog).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListTKP(oFormBlog, page, perPage);
            });
            $(oFormBlog).find('#cbo_nuber_record_page').change(function () {
                var page = $(oFormBlog).find('#_currentPage').val();
                var perPages = $(oFormBlog).find('#cbo_nuber_record_page').val();
                myClass.loadListTKP(oFormBlog, page, perPages);
            });
        }
    });
}
/**
 * Danh sách Phân tích ngành
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_About.prototype.loadListPTN = function (oFormBlog,numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = myClass.urlPath + '/loadListPTN';
    var data = $(oFormBlog).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    data += '&category=BAO_CAO_PTN';
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-blog-container").html(arrResult);
            $(oFormBlog).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListPTN(oFormBlog, page, perPage);
            });
            $(oFormBlog).find('#cbo_nuber_record_page').change(function () {
                var page = $(oFormBlog).find('#_currentPage').val();
                var perPages = $(oFormBlog).find('#cbo_nuber_record_page').val();
                myClass.loadListPTN(oFormBlog, page, perPages);
            });
        }
    });
}
/**
 * Danh sách Phân tích cổ phiếu
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_About.prototype.loadListPTCP = function (oFormBlog,numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = myClass.urlPath + '/loadListPTCP';
    var data = $(oFormBlog).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    data += '&category=BAO_CAO_PTCP';
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-blog-container").html(arrResult);
            $(oFormBlog).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListPTCP(oFormBlog, page, perPage);
            });
            $(oFormBlog).find('#cbo_nuber_record_page').change(function () {
                var page = $(oFormBlog).find('#_currentPage').val();
                var perPages = $(oFormBlog).find('#cbo_nuber_record_page').val();
                myClass.loadListPTCP(oFormBlog, page, perPages);
            });
        }
    });
}
/**
 * Đọc thêm
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_About.prototype.reader = function (id) {
    var myClass = this;
    var url = myClass.baseUrl + '/client/about/reader';
    var data = '_token=' +  $('#_token').val();
    data += '&id=' +  id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#reader').html(arrResult);
            $('#reader').modal('show');
        }
    });
}
