function JS_Home(baseUrl) {
    NclLib.loadding();
    this.urlPath = baseUrl;
}
JS_Home.prototype.alerMesage = function(nameMessage,icon,color){
    Swal.fire({
        position: 'top-end',
        icon: icon,
        title: nameMessage,
        color: color,
        showConfirmButton: false,
        width:'50%',
        timer: 2500
      })
}

/**
 * Hàm load màn hình index
 *
 * @return void
 */
JS_Home.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmLoadlist_list_tap1';
    var oFormBlog = 'form#frmLoadlist_blog';
    NclLib.menuActive('.link-home');
    $('.chzn-select').chosen({ height: '100%', width: '100%' });    
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $("#myInput").click(function(){
        $("#overSearch").toggleClass('closed');
    });
}
JS_Home.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Home.prototype.loadList = function () {
    var myClass = this;
    var oForm = 'form#frmLoadlist_list';
    // var loadding = NclLib.loadding();
    var url = this.urlPath + '/loadList';
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            window.location.href = "{{ route('trang-chu')}}";
        }
    });
}
/**
 * Load màn hình chỉ số top
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Home.prototype.signIn = function () {
    var myClass = this;
    var oForm = 'form#frm_sign_in';
    if ($("#username").val() == '') {
        var nameMessage = 'Mã bệnh nhân không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        this.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password").val() == '') {
        var nameMessage = 'Mật khẩu không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        this.alerMesage(nameMessage,icon,color);
        return false;
    }
    var url = this.urlPath + '/checkLogin';
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POSt",
        data: data,
        success: function (arrResult) {
            if(arrResult.status == true){
               window.location.href = "/";
            }else{
                    var nameMessage = 'Thông tin chưa chính xác!';
                    var icon = 'warning';
                    var color = '#f5ae67';
                    NclLib.alerMesage(nameMessage,icon,color);
                    return false;
            }
        }
    });
}
/**
 * Load màn hình chỉ số top
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Home.prototype.loadListTop = function () {
    var myClass = this;
    var oForm = 'form#frmLoadlist_list';
    var url = this.urlPath + '/loadListTop';
    $.ajax({
        url: url,
        type: "GET",
        success: function (arrResult) {
            $("#table-container-loadListTop").html(arrResult);
            myClass.loadevent(oForm);
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
JS_Home.prototype.loadListBlog = function (oFormBlog,numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadListBlog';
    var data = $(oFormBlog).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-blog-container").html(arrResult);
            $(oFormBlog).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListBlog(oFormBlog, page, perPage);
            });
            $(oFormBlog).find('#cbo_nuber_record_page').change(function () {
                var page = $(oFormBlog).find('#_currentPage').val();
                var perPages = $(oFormBlog).find('#cbo_nuber_record_page').val();
                myClass.loadListBlog(oFormBlog, page, perPages);
            });
            myClass.loadevent(oFormBlog);
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
JS_Home.prototype.loadListTap1 = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    // var loadding = NclLib.loadding();
    var url = this.urlPath + '/loadListTap1';
    data = 'offset=' + numberPage;
    data += '&limit=' + perPage;
    var oForm = 'form#frmLoadlist_Bank'
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container-bank").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadListTap1(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPages = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadListTap1(oForm, page, perPages);
            });
            // loadding.go(100);
            myClass.loadevent(oForm);
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
JS_Home.prototype.inFor = function (id) {
    var myClass = this;
    console.log(id)
    var url = this.urlPath + '/inForFacilities';
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: id,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
        }
    });
}
/**
 * Load màn hình chỉ số top
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Home.prototype.getSearch = function (url) {
    console.log(url)
    var myClass = this;
    // window.location.replace(myClass.baseUrl+'/searchschedule');
}